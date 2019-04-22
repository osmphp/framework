import fontSpy from './fontSpy';
import $ from 'jquery';

// TLDR; always trigger window resize after custom font is loaded, otherwise element size calculations may
// be wrong.
//
// We noticed this issue first while implementing menu component: when menu is first shown its icon font symbols
// may have incorrect width as underlying icon font is not loaded yet. As a result, menu may calculate
// incorrect position and/or direction.
//
// This issue is described in detail in section "Optimizing loading and rendering" of:
//      https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/webfont-optimization
//
// The solution is to trigger window resize after font is loaded. It is responsibility of each component to readjust
// itself after each window resize event.

fontSpy('Material Icons', { glyphs: 'format_bold', success: () => { $(window).trigger('resize'); }});
