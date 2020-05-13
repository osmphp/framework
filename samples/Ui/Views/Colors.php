<?php

namespace Osm\Samples\Ui\Views;

use Osm\Framework\Views\View;
use Osm\Samples\Ui\Color;

/**
 * @property string[] $brand_colors @required @part
 * @property string[] $emotion_colors @required @part
 * @property string[] $additional_backgrounds @required @part
 * @property string[] $additional_foregrounds @required @part
 *
 * Computed properties:
 *
 * @property Color[] $background_colors @required
 * @property Color[] $foreground_colors @required
 */
class Colors extends View
{
    public $template = 'Osm_Samples_Ui.colors';

    protected function default($property) {
        switch ($property) {
            case 'brand_colors': return [
                'primary-dark',
                'primary',
                'primary-light',
                'secondary-dark',
                'secondary',
                'secondary-light',
            ];
            case 'emotion_colors': return [
                'success',
                'warning',
                'error',
                'danger',
            ];
            case 'additional_backgrounds': return [
                'space',
                'message',
            ];
            case 'additional_foregrounds': return [
                'disabled',
                'delimiter',
            ];

            case 'background_colors':
                $result = ['' => Color::new([
                    'title' => '(default)'
                ])];

                foreach ($this->brand_colors as $name) {
                    $result[$name] = Color::new([
                        'brand' => true,
                        'css' => $this->css('-on-', $name),
                        'title' => $name,
                    ]);
                }

                foreach ($this->emotion_colors as $name) {
                    $result[$name] = Color::new([
                        'emotion' => true,
                        'css' => $this->css('-on-', $name),
                        'title' => $name,
                    ]);
                }

                foreach ($this->additional_backgrounds as $name) {
                    $result[$name] = Color::new([
                        'additional' => true,
                        'css' => $this->css('-on-', $name),
                        'title' => $name,
                    ]);
                }

                return $result;
            case 'foreground_colors':
                $result = ['' => Color::new([
                    'title' => ''
                ])];

                foreach ($this->brand_colors as $name) {
                    $result[$name] = Color::new([
                        'brand' => true,
                        'css' => $this->css('-', $name),
                        'title' => $name,
                    ]);
                }

                foreach ($this->emotion_colors as $name) {
                    $result[$name] = Color::new([
                        'emotion' => true,
                        'css' => $this->css('-', $name),
                        'title' => $name,
                    ]);
                }

                foreach ($this->additional_foregrounds as $name) {
                    $result[$name] = Color::new([
                        'additional' => true,
                        'css' => $this->css('-', $name),
                        'title' => $name,
                    ]);
                }

                return $result;
        }
        return parent::default($property);
    }

    protected function css($prefix, $name) {
        return implode(' ', array_map(function($name) use ($prefix) {
            return "{$prefix}{$name}";
        }, explode(' ', $name)));
    }

    public function isApplicable(Color $background, Color $foreground) {
        if ($background->brand) {
            if ($foreground->brand) {
                return false;
            }

            return true;
        }
        if ($background->emotion) {
            if ($foreground->brand) {
                return false;
            }
            if ($foreground->emotion) {
                return false;
            }

            return true;
        }

        return true;
    }
}