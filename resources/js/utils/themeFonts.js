export const THEME_FONT_OPTIONS = [
  { label: 'Instrument Sans / Inter (Default)', value: 'Instrument Sans, Inter, sans-serif' },
  { label: 'Inter (Webfont)', value: 'Inter, system-ui, sans-serif', bunnySlug: 'inter:400,500,600,700,800' },
  { label: 'Poppins (Webfont)', value: 'Poppins, sans-serif', bunnySlug: 'poppins:400,500,600,700,800' },
  { label: 'Roboto (Webfont)', value: 'Roboto, sans-serif', bunnySlug: 'roboto:400,500,700' },
  { label: 'Open Sans (Webfont)', value: '"Open Sans", sans-serif', bunnySlug: 'open-sans:400,600,700,800' },
  { label: 'Lato (Webfont)', value: 'Lato, sans-serif', bunnySlug: 'lato:400,700,900' },
  { label: 'Montserrat (Webfont)', value: 'Montserrat, sans-serif', bunnySlug: 'montserrat:400,500,600,700,800' },
  { label: 'Playfair Display (Serif Webfont)', value: '"Playfair Display", Georgia, serif', bunnySlug: 'playfair-display:400,600,700,800' },
  { label: 'Merriweather (Serif Webfont)', value: 'Merriweather, Georgia, serif', bunnySlug: 'merriweather:400,700,900' },
  { label: 'JetBrains Mono (Webfont)', value: '"JetBrains Mono", ui-monospace, monospace', bunnySlug: 'jetbrains-mono:400,600,700' },
  { label: 'System UI (Native)', value: 'system-ui, -apple-system, BlinkMacSystemFont, sans-serif' },
  { label: 'Georgia (Classic Serif)', value: 'Georgia, Cambria, serif' },
  { label: 'Monospace (System)', value: 'ui-monospace, SFMono-Regular, Menlo, monospace' },
];

export const DEFAULT_TYPOGRAPHY_ELEMENTS = {
  p: { font_family: 'default', color: '', font_size: '16px', font_weight: 'default', line_height: '1.6' },
  h1: { font_family: 'default', color: '', font_size: '2.5em', font_weight: '800', line_height: '1.2' },
  h2: { font_family: 'default', color: '', font_size: '2em', font_weight: '700', line_height: '1.25' },
  h3: { font_family: 'default', color: '', font_size: '1.5em', font_weight: '700', line_height: '1.3' },
  h4: { font_family: 'default', color: '', font_size: '1.25em', font_weight: '600', line_height: '1.35' },
};

export function getBunnyFontsHref(fontValues = []) {
  const slugs = new Set();

  for (const val of fontValues) {
    if (!val || val === 'default') continue;
    const match = THEME_FONT_OPTIONS.find((opt) => opt.value === val && opt.bunnySlug);
    if (match) {
      slugs.add(match.bunnySlug);
    }
  }

  if (slugs.size === 0) return null;
  return `https://fonts.bunny.net/css?family=${Array.from(slugs).join('|')}&display=swap`;
}

export function resolveElementStyle(tag, typography = {}, colors = {}) {
  const el = typography?.elements?.[tag] || DEFAULT_TYPOGRAPHY_ELEMENTS[tag] || {};
  const isParagraph = tag === 'p';

  const fontFamily =
    el.font_family && el.font_family !== 'default'
      ? el.font_family
      : isParagraph
        ? typography.body_font || 'Instrument Sans, Inter, sans-serif'
        : typography.heading_font || 'Instrument Sans, Inter, sans-serif';

  const fontWeight =
    el.font_weight && el.font_weight !== 'default'
      ? el.font_weight
      : isParagraph
        ? '400'
        : typography.heading_weight || '700';

  const color = el.color || (isParagraph ? colors.text || '#1e293b' : colors.secondary || '#0f172a');

  return {
    fontFamily,
    color,
    fontSize: el.font_size || DEFAULT_TYPOGRAPHY_ELEMENTS[tag]?.font_size || '16px',
    fontWeight,
    lineHeight: el.line_height || (isParagraph ? typography.line_height || '1.6' : '1.25'),
  };
}
