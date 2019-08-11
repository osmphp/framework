# CSS #

## SASS Variables ##

Most global SASS variables are defined in `vendor/dubysa/components/src/Ui/Aba/resources/css/_variables.scss`.

{{ page_toc }}

### Colors ###

    $color--primary: black;                 // primary line and font color used by most visible elements
    $color--active: darkolivegreen;         // color used by elements having focus or hover to stand out
                                            // from other elements

### Background Colors ###

    $background-color--primary: white;      // primary background color seen in most of screen property

### Z-Indexes ###

    // Z Indexes are used not only to set HTML element `z-index` property (which positions one elements
    // closer to screen surface than others) but also to add shadows (elements which fly higher have larger shadows).
    //
    // These variables are based [on material design guidelines](https://material.io/design/environment/elevation.html).

    $z-index--card: 1;
    $z-index--button--raised: 2;
    $z-index--button--floating-action: 6;
    $z-index--menu: 8;
    $z-index--button--raised--pressed: 8;
    $z-index--snack-bar: 8;
    $z-index--button--floating-action--pressed: 12;

### Other ###

    $line-height--base: 1.5;                // Default line height (1.5 ratio means that if font size is 16px,
                                            // then line height is 24px)

