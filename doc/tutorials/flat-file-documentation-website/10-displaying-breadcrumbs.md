# Displaying Breadcrumbs

Expected Result
----------------------------------------

Application should show breadcrumbs at the top of the page:

<http://127.0.0.1/docs/tutorials/hello-world.html>

![Displaying breadcrumbs](09-displaying-breadcrumbs.png)

Steps To Implement:
----------------------------------------

{{ toc }}

## Modifying `Frontend` Controller

Modify `app/src/Docs/Controllers/Frontend.php`

    <?php
    
    namespace App\Docs\Controllers;
    
    use App\Docs\Module;
    use App\Docs\Page;
    use App\Docs\Views\Breadcrumbs;
    use App\Docs\Views\Html;
    use Manadev\Core\App;
    use Manadev\Framework\Http\Controller;
    use Manadev\Framework\Http\Responses;
    use Manadev\Framework\Views\Views\Container;
    
    /**
     * @property Page $page @required
     * @property Module $module @required
     * @property Responses $responses @required
     */
    class Frontend extends Controller
    {
        protected function default($property) {
            global $m_app; /* @var App $m_app */
    
            switch ($property) {
                case 'module':
                    return $m_app->modules['App_Docs'];
                case 'page':
                    return $this->module->page;
                case 'responses':
                    return $m_app[Responses::class];
            }
    
            return parent::default($property);
        }
    
        public function show() {
            return m_layout([
                '@include' => 'base',
                '#page' => [
                    'title' => $this->page->title,
                    'content' => Container::new([
                        'views' => [
                            'breadcrumbs' => Breadcrumbs::new(['page' => $this->page]),
                            'main' => Html::new(['page' => $this->page]),
                        ],
                    ]),
                ],
            ]);
        }
    
        public function image() {
            return $this->responses->image($this->module->image);
        }
    }

## Modifying `PageFinder` class

`app/src/Docs/PageFinder.php`

    <?php
    
    namespace App\Docs;
    
    use App\Docs\Hints\SettingsHint;
    use Manadev\Core\App;
    use Manadev\Core\Exceptions\NotSupported;
    use Manadev\Core\Object_;
    use Manadev\Framework\Settings\Settings;
    
    /**
     * @property Settings|SettingsHint $settings @required
     * @property string $doc_root @required
     */
    class PageFinder extends Object_
    {
        protected function default($property) {
            global $m_app; /* @var App $m_app */
    
            switch ($property) {
                case 'settings':
                    return $m_app->settings;
                case 'doc_root':
                    return $this->settings->doc_root;
            }
    
            return parent::default($property);
        }
    
        /**
         * Returns .md page file by URL or returns null if not found
         *
         * @param string $url
         * @return Page
         */
        public function find($url) {
            if ($url === '/') {
                // home page is rendered from 'index.md'
                if (is_file($filename = $this->doc_root . '/index.md')) {
                    return Page::new(['name' => $filename]);
                }
            }
    
            if (mb_strrpos($url, '.html') !== mb_strlen($url) - mb_strlen('.html')) {
                // if page URL doesn't end with configured '.html' suffix, show that page is not found
                return null;
            }
    
            // page URL ends with '.html' suffix. Remove suffix from URL
            $url = mb_substr($url, 0, mb_strlen($url) - mb_strlen('.html'));
    
            // handle page path. There should always be at least one '/' in URL as all page URLs start with '/'.
    
            // find position of last '/' in URL and define path and filename
            $pos = mb_strrpos($url, '/');
            $path = $this->doc_root . mb_substr($url, 0, $pos);
            $filename = mb_substr($url, $pos + 1);
    
            // if path is not a directory or filename is empty, show that page is not found
            if (!is_dir($path) || !$filename) {
                return null;
            }
    
            // iterate through all files in 'path' directory and find file with or without preceding sort order.
            foreach (new \DirectoryIterator($path) as $fileInfo) {
                // skip '.' and '..' directory items
                if ($fileInfo->isDot() || $fileInfo->isDir()) {
                    continue;
                }
    
                if (preg_match("/(?:\\d+-)?" . preg_quote($filename) . "\\.md/u", $fileInfo->getFilename())) {
                    return Page::new(['name' => "{$path}/{$fileInfo->getFilename()}"]);
                    }
                }
    
            // If underlying directory doesn't exist we return that page doesn't exist
            return null;
        }
    
        /**
         * @param Page $page
         * @return Page[]
         */
        public function findChildPages(Page $page) {
            $result = [];
    
            if ($page->name == $this->doc_root . '/index.md') {
                $path = $this->doc_root;
            }
            else {
                if (!preg_match(Page::FILENAME_PATTERN, basename($page->name), $match)) {
                    throw new NotSupported();
                }
    
                $path = dirname($page->name) . '/' . $match['name'];
            }
    
            if (!is_dir($path)) {
                return $result;
            }
    
            foreach (new \DirectoryIterator($path) as $fileInfo) {
                if ($fileInfo->isDot()) {
                    continue;
                }
    
                if (starts_with($fileInfo->getFilename(), '.')) {
                    continue;
                }
    
                if ($fileInfo->isDir()) {
                    continue;
                }
    
                // don't add root page itself to the children of root page
                if ($path == $this->doc_root && $fileInfo->getFilename() == 'index.md') {
                    continue;
                }
    
                if (preg_match(Page::FILENAME_PATTERN, $fileInfo->getFilename())) {
                    $result[] = Page::new(['name' => "{$path}/{$fileInfo->getFilename()}"]);
                }
            }
    
            usort($result, function(Page $a, Page $b) {
                if ($a->name < $b->name) return -1;
                if ($a->name > $b->name) return 1;
                return 0;
            });
    
            return $result;
        }
    
        public function findParentPages(Page $page) {
            if ($page->name === $this->doc_root . '/index.md') {
                return [];
            }
    
            $result = [Page::new(['name' => $this->doc_root . '/index.md'])];
    
            // $path always starts with '/'
            $url = strtr(mb_substr($page->name, mb_strlen($this->doc_root)), '\\', '/');
    
            for ($oldPos = 0, $pos = mb_strpos($url, '/', 1); $pos !== false;
                $oldPos = $pos, $pos = mb_strpos($url, '/', $pos + 1))
            {
                $path = $this->doc_root . mb_substr($url, 0, $oldPos);
                $name = mb_substr($url, $oldPos + 1, $pos - ($oldPos + 1));
    
                 foreach (new \DirectoryIterator($path) as $fileInfo) {
                    if ($fileInfo->isDot() || $fileInfo->isDir()) {
                        continue;
                    }
    
                    if (!preg_match(Page::FILENAME_PATTERN, $fileInfo->getFilename(), $match)) {
                        continue;
                    }
    
                    if ($match['name'] != $name) {
                        continue;
                    }
    
                     $result[] = Page::new(['name' => "{$path}/{$fileInfo->getFilename()}"]);
                     break;
                 }
            }
    
            return $result;
        }
    }

## Creating New `Breadcrumbs` View
  
Create new PHP class `app/src/Docs/Views/Breadcrumbs.php`:

    <?php
    
    namespace App\Docs\Views;
    
    use App\Docs\Page;
    use App\Docs\PageFinder;
    use Manadev\Core\App;
    use Manadev\Framework\Views\View;
    
    /**
     * @property Page $page @required
     * @property Page[] $parent_pages @required
     * @property PageFinder $page_finder @required
     */
    class Breadcrumbs extends View
    {
        public $template = 'App_Docs.breadcrumbs';
    
        protected function default($property) {
            global $m_app; /* @var App $m_app */
    
            switch ($property) {
                case 'page_finder': return $m_app[PageFinder::class];
                case 'parent_pages': return $this->page_finder->findParentPages($this->page);
            }
            return parent::default($property);
        }
    }    
    
## Creating New `breadcrumbs` View Template 

Create new view template `app/src/Docs/frontend/views/breadcrumbs.blade.php`:

    <?php
    /* @var \App\Docs\Views\Breadcrumbs $view */
    ?>
    @if (count($view->parent_pages))
        <div class="breadcrumbs">
            <nav class="breadcrumbs__items">
                @foreach ($view->parent_pages as $page)
                    <a href="{{ $page->url }}">{{ $page->title }}</a>
                    @if (!$loop->last) &gt; @endif
                @endforeach
            </nav>
        </div>
    @endif

