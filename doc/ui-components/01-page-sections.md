# Page Sections #


Divide pages vertically into sections:

    // TODO: image with several page sections

Page sections take full width, flow one after another and limit width of their content to `$page-width` theme variable.

Page section markup:

    <div class="page-section">
        <!-- ... -->
    </div>

If applicable, use semantically correct element instead of `<div>`. For instance:

    <header class="page-section">
        <!-- ... -->
    </header>
    <div class="page-section">
        <!-- ... -->
    </div>
    <footer class="page-section">
        <!-- ... -->
    </footer>

By default, page sections are transparent. However you can apply theme colors to them using color modifiers:

    <div class="page-section -primary">
        <!-- ... -->
    </div>
    <div class="page-section">
        <!-- ... -->
    </div>
    <div class="page-section - -secondary">
        <!-- ... -->
    </div>
    <div class="page-section">
        <!-- ... -->
    </div>

> **Note**. To use page sections, [add `dubysa/app` package](#) to your project.