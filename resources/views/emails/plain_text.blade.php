@if (isset($content))
{!! trim(html_entity_decode(strip_tags(preg_replace(['/<br\s*\/?>/i', '/<\/(p|div|h[1-6]|li|tr)>/i'], "\n", $content)), ENT_QUOTES | ENT_HTML5, 'UTF-8')) !!}
@endif
