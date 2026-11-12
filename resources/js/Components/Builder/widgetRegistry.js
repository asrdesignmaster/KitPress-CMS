export const WIDGET_CATEGORIES = [
  { id: 'basic', label: 'Basic' },
  { id: 'pro', label: 'Pro Elements' },
  { id: 'media', label: 'Media & Interactive' },
];

export const WIDGET_DEFINITIONS = [
  // BASIC
  {
    type: 'heading',
    label: 'Heading',
    category: 'basic',
    badge: 'H',
    defaults: () => ({
      type: 'heading',
      tag: 'h2',
      content: 'Design Without Limits',
      fontSize: '32px',
      fontWeight: '700',
      color: '#0f172a',
      textAlign: 'left',
      marginBottom: '12px',
    }),
  },
  {
    type: 'text',
    label: 'Text Editor',
    category: 'basic',
    badge: '¶',
    defaults: () => ({
      type: 'text',
      content: 'Craft high-converting pages with a reactive visual tree engine. Click to edit typography, spacing, and layout in real time.',
      fontSize: '16px',
      lineHeight: '1.7',
      color: '#475569',
      textAlign: 'left',
      marginBottom: '16px',
    }),
  },
  {
    type: 'image',
    label: 'WebP Image',
    category: 'basic',
    badge: '🖼',
    defaults: () => ({
      type: 'image',
      url: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1000&q=80',
      alt: 'Featured visual',
      width: '100%',
      borderRadius: '12px',
      shadow: 'md',
      textAlign: 'center',
      marginBottom: '16px',
    }),
  },
  {
    type: 'button',
    label: 'Button',
    category: 'basic',
    badge: 'BTN',
    defaults: () => ({
      type: 'button',
      content: 'Get Started Free',
      url: '#',
      size: 'md',
      backgroundColor: '#4f46e5',
      textColor: '#ffffff',
      borderRadius: '8px',
      textAlign: 'left',
      showArrow: true,
      marginBottom: '12px',
    }),
  },
  {
    type: 'spacer',
    label: 'Spacer',
    category: 'basic',
    badge: '↕',
    defaults: () => ({
      type: 'spacer',
      height: '32px',
    }),
  },
  {
    type: 'divider',
    label: 'Divider',
    category: 'basic',
    badge: '—',
    defaults: () => ({
      type: 'divider',
      style: 'solid',
      color: '#cbd5e1',
      thickness: '1px',
      width: '100%',
      marginTop: '16px',
      marginBottom: '16px',
    }),
  },

  // PRO ELEMENTS
  {
    type: 'icon_box',
    label: 'Icon Box',
    category: 'pro',
    badge: '★',
    defaults: () => ({
      type: 'icon_box',
      icon: '⚡',
      title: 'Lightning Performance',
      description: 'Zero legacy hook overhead with instant WebP asset delivery and clean JSON rendering.',
      iconBg: '#eef2ff',
      iconColor: '#4f46e5',
      backgroundColor: '#ffffff',
      titleColor: '#0f172a',
      textColor: '#64748b',
      borderRadius: '14px',
      padding: '24px',
      textAlign: 'left',
      marginBottom: '16px',
    }),
  },
  {
    type: 'image_box',
    label: 'Image Box',
    category: 'pro',
    badge: '🎴',
    defaults: () => ({
      type: 'image_box',
      url: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80',
      title: 'Visual Analytics',
      description: 'Real-time SEO scoring, content density checks, and automated sitemap indexing.',
      buttonText: 'Learn more',
      buttonUrl: '#',
      backgroundColor: '#ffffff',
      borderRadius: '14px',
      marginBottom: '16px',
    }),
  },
  {
    type: 'counter',
    label: 'Counter',
    category: 'pro',
    badge: '123',
    defaults: () => ({
      type: 'counter',
      prefix: '',
      number: '99.9',
      suffix: '%',
      title: 'Core Web Vitals Score',
      numberColor: '#4f46e5',
      titleColor: '#475569',
      fontSize: '42px',
      textAlign: 'center',
      marginBottom: '16px',
    }),
  },
  {
    type: 'progress_bar',
    label: 'Progress Bar',
    category: 'pro',
    badge: '▬',
    defaults: () => ({
      type: 'progress_bar',
      title: 'PageSpeed Optimization',
      percentage: 94,
      barColor: '#4f46e5',
      trackColor: '#e2e8f0',
      showPercentage: true,
      marginBottom: '16px',
    }),
  },
  {
    type: 'testimonial',
    label: 'Testimonial',
    category: 'pro',
    badge: '“”',
    defaults: () => ({
      type: 'testimonial',
      quote: 'Switching to this Laravel + Vue CMS cut our page load times in half while giving our marketing team an Elementor-class visual builder.',
      authorName: 'Sarah Connor',
      authorRole: 'VP of Engineering, TechCorp',
      avatarUrl: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80',
      rating: 5,
      backgroundColor: '#ffffff',
      textColor: '#334155',
      borderRadius: '16px',
      marginBottom: '16px',
    }),
  },
  {
    type: 'pricing_table',
    label: 'Pricing Table',
    category: 'pro',
    badge: '$',
    defaults: () => ({
      type: 'pricing_table',
      title: 'Pro Studio',
      subtitle: 'For growing teams & agencies',
      badge: 'Most Popular',
      currency: '$',
      price: '49',
      period: '/month',
      features: [
        'Unlimited Visual Builder Pages',
        'Automated WebP Conversion',
        'Yoast-Style Live SEO Suite',
        'E-Commerce Ready Schema',
      ],
      buttonText: 'Start 14-Day Trial',
      buttonUrl: '#',
      accentColor: '#4f46e5',
      highlighted: true,
      backgroundColor: '#ffffff',
      marginBottom: '16px',
    }),
  },
  {
    type: 'alert',
    label: 'Alert / Notice',
    category: 'pro',
    badge: '!',
    defaults: () => ({
      type: 'alert',
      variant: 'info',
      title: 'Pro Tip',
      description: 'All uploaded images are automatically converted to optimized WebP format on the server.',
      dismissible: true,
      marginBottom: '16px',
    }),
  },

  // MEDIA & INTERACTIVE
  {
    type: 'accordion',
    label: 'Accordion / FAQ',
    category: 'media',
    badge: '▾',
    defaults: () => ({
      type: 'accordion',
      accentColor: '#4f46e5',
      items: [
        {
          title: 'How does the dual-mode editor work?',
          content: 'You can switch between the Classic TipTap ProseMirror editor and the Elementor-style Visual Tree Builder at any time.',
        },
        {
          title: 'Are images automatically optimized?',
          content: 'Yes, every uploaded JPG or PNG is intercepted server-side, converted to WebP, and resized into Thumbnail, Medium, and Large variants.',
        },
      ],
      marginBottom: '16px',
    }),
  },
  {
    type: 'tabs',
    label: 'Tabs',
    category: 'media',
    badge: '⊟',
    defaults: () => ({
      type: 'tabs',
      accentColor: '#4f46e5',
      items: [
        {
          title: 'Overview',
          content: 'Built on Laravel 11 + Vue 3 + Inertia for instant SPA-like administration without legacy WordPress hook overhead.',
        },
        {
          title: 'SEO Engine',
          content: 'Includes live focus keyword analysis, SERP snippet preview, OpenGraph cards, and dynamic XML sitemaps.',
        },
        {
          title: 'Performance',
          content: 'One-click route/config caching, lazy loading enforcement, and database revision cleanup tools.',
        },
      ],
      marginBottom: '16px',
    }),
  },
  {
    type: 'slider',
    label: 'Carousel Slider',
    category: 'media',
    badge: '▤',
    defaults: () => ({
      type: 'slider',
      height: '340px',
      borderRadius: '14px',
      slides: [
        {
          image: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&q=80',
          title: 'Modern Visual Publishing',
          subtitle: 'Build responsive multi-column layouts in seconds.',
          buttonText: 'Explore Features',
          buttonUrl: '#',
        },
        {
          image: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1200&q=80',
          title: 'Automated WebP & SEO',
          subtitle: 'Rank higher with built-in Yoast-style content analysis.',
          buttonText: 'View Demo',
          buttonUrl: '#',
        },
      ],
      marginBottom: '16px',
    }),
  },
  {
    type: 'video',
    label: 'Video Embed',
    category: 'media',
    badge: '▶',
    defaults: () => ({
      type: 'video',
      url: 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
      aspectRatio: '16/9',
      borderRadius: '12px',
      marginBottom: '16px',
    }),
  },
  {
    type: 'social_icons',
    label: 'Social Icons',
    category: 'media',
    badge: '🌐',
    defaults: () => ({
      type: 'social_icons',
      shape: 'circle',
      textAlign: 'left',
      items: [
        { platform: 'github', label: 'GitHub', url: 'https://github.com', color: '#0f172a' },
        { platform: 'twitter', label: 'X / Twitter', url: 'https://x.com', color: '#0284c7' },
        { platform: 'linkedin', label: 'LinkedIn', url: 'https://linkedin.com', color: '#1d4ed8' },
      ],
      marginBottom: '12px',
    }),
  },
];

export function createWidget(type) {
  const def = WIDGET_DEFINITIONS.find((w) => w.type === type);
  const id = 'w_' + Date.now() + '_' + Math.floor(Math.random() * 10000);
  if (!def) {
    return { id, type, content: '' };
  }
  return { id, ...def.defaults() };
}

export function createSectionPreset(presetId) {
  const ts = Date.now();
  if (presetId === 'hero_cta') {
    return {
      id: `sec_${ts}`,
      name: 'Pro Hero Banner',
      containerWidth: 'boxed',
      backgroundColor: '#0f172a',
      backgroundGradient: '',
      textColor: '#ffffff',
      paddingTop: '64px',
      paddingBottom: '64px',
      columns: [
        {
          id: `col_${ts}_1`,
          width: '55%',
          padding: '12px',
          widgets: [
            { ...createWidget('heading'), tag: 'h1', content: 'Build Faster with Elementor-Grade Visuals', fontSize: '40px', color: '#ffffff' },
            { ...createWidget('text'), content: 'Combine Laravel 11 speed with a reactive Vue 3 visual tree builder, automated WebP compression, and real-time SEO.', color: '#cbd5e1', fontSize: '17px' },
            { ...createWidget('button'), content: 'Launch Studio', backgroundColor: '#6366f1', size: 'lg' },
          ],
        },
        {
          id: `col_${ts}_2`,
          width: '45%',
          padding: '12px',
          widgets: [
            { ...createWidget('image'), url: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=900&q=80', borderRadius: '16px' },
          ],
        },
      ],
    };
  }

  if (presetId === 'pricing_3col') {
    const starter = createWidget('pricing_table');
    starter.title = 'Starter';
    starter.price = '19';
    starter.badge = '';
    starter.highlighted = false;

    const pro = createWidget('pricing_table');

    const enterprise = createWidget('pricing_table');
    enterprise.title = 'Enterprise';
    enterprise.price = '99';
    enterprise.badge = 'Custom SLA';
    enterprise.highlighted = false;

    return {
      id: `sec_${ts}`,
      name: 'Pricing Tiers',
      containerWidth: 'boxed',
      backgroundColor: '#f8fafc',
      textColor: '#0f172a',
      paddingTop: '56px',
      paddingBottom: '56px',
      columns: [
        { id: `col_${ts}_1`, width: '33.33%', padding: '8px', widgets: [starter] },
        { id: `col_${ts}_2`, width: '33.33%', padding: '8px', widgets: [pro] },
        { id: `col_${ts}_3`, width: '33.33%', padding: '8px', widgets: [enterprise] },
      ],
    };
  }

  // Default 3-feature icon grid
  const f1 = createWidget('icon_box');
  const f2 = { ...createWidget('icon_box'), icon: '🛡️', title: 'Explicit RBAC Security', description: 'Clean Admin, Editor, and Author policies with zero legacy hook pollution.', iconBg: '#ecfdf5', iconColor: '#059669' };
  const f3 = { ...createWidget('icon_box'), icon: '🚀', title: 'Built-in Yoast SEO', description: 'Live keyword density, SERP preview, OpenGraph cards, and XML sitemap generation.', iconBg: '#faf5ff', iconColor: '#7c3aed' };

  return {
    id: `sec_${ts}`,
    name: '3-Column Feature Grid',
    containerWidth: 'boxed',
    backgroundColor: '#ffffff',
    textColor: '#0f172a',
    paddingTop: '56px',
    paddingBottom: '56px',
    columns: [
      { id: `col_${ts}_1`, width: '33.33%', padding: '8px', widgets: [f1] },
      { id: `col_${ts}_2`, width: '33.33%', padding: '8px', widgets: [f2] },
      { id: `col_${ts}_3`, width: '33.33%', padding: '8px', widgets: [f3] },
    ],
  };
}

