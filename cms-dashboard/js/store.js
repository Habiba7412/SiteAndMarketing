// Global State Store with MySQL DB Synchronization via fetch API

const INITIAL_DATA = {
  blogs: [
    {
      id: "blog-1",
      title: "How AI is Revolutionizing Enterprise IT Infrastructure in 2026",
      slug: "how-ai-revolutionizing-enterprise-it",
      content: "<p>Artificial intelligence is no longer just a buzzword; it's the core engine driving enterprise IT infrastructure. In 2026, we see a migration toward autonomous cloud networking and self-healing servers.</p>",
      excerpt: "Explore how artificial intelligence is transforming enterprise IT operations, optimizing hybrid clouds, and paving the way for autonomous, self-healing systems.",
      category: "Enterprise IT",
      tags: ["AI", "Cloud", "Infrastructure"],
      author: "Sarah Connor (CTO)",
      coverImage: "https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80",
      seoTitle: "AI Revolution in Enterprise IT Infrastructure | MyITCompany",
      metaDescription: "Understand how AI is driving next-gen IT infrastructure in 2026. Learn about self-healing servers, edge nodes, and cloud optimization.",
      readingTime: "5 min read",
      status: "Published",
      isFeatured: true,
      dateCreated: "2026-07-15T10:30:00Z"
    }
  ],
  categories: [
    { id: "cat-1", name: "Enterprise IT", slug: "enterprise-it", count: 12 },
    { id: "cat-2", name: "Security", slug: "security", count: 8 },
    { id: "cat-3", name: "Cloud Computing", slug: "cloud-computing", count: 15 }
  ],
  tags: [
    { id: "tag-1", name: "AI", slug: "ai" },
    { id: "tag-2", name: "Cloud", slug: "cloud" }
  ],
  menus: [
    {
      id: "menu-main",
      name: "Header Navigation",
      status: "Active",
      items: [
        { id: "m-1", name: "Home", type: "internal", url: "index.php", target: "_self" },
        { id: "m-2", name: "About", type: "internal", url: "about.php", target: "_self" },
        { id: "m-3", name: "Services", type: "internal", url: "services.php", target: "_self" },
        { id: "m-4", name: "Projects", type: "internal", url: "projects.php", target: "_self" },
        { id: "m-5", name: "Blog", type: "internal", url: "blog.php", target: "_self" },
        { id: "m-6", name: "Contact", type: "internal", url: "contact.php", target: "_self" }
      ]
    }
  ],
  users: [
    {
      id: "usr-1",
      name: "Jack Devlin",
      email: "jack.devlin@myitcompany.com",
      phone: "+1 (555) 019-2834",
      status: "Active",
      role: "Super Admin",
      avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80",
      lastLogin: "2026-07-21T09:12:00Z",
      registrationDate: "2024-01-10T08:30:00Z"
    }
  ],
  roles: [
    {
      role: "Super Admin",
      description: "Full system access to all configurations and data.",
      permissions: {
        dashboard: ["view"],
        users: ["view", "create", "update", "delete"],
        blog: ["view", "create", "update", "delete"],
        menu: ["view", "create", "update", "delete"],
        seo: ["view", "create", "update", "delete"],
        media: ["view", "create", "update", "delete"],
        contact: ["view", "create", "update", "delete"],
        email: ["view", "create", "update", "delete"],
        settings: ["view", "create", "update", "delete"]
      }
    }
  ],
  contacts: [],
  emailSettings: {
    smtpHost: "smtp.gmail.com",
    smtpPort: "587",
    smtpUsername: "notifications@myitcompany.com",
    smtpPassword: "••••••••••••••••",
    encryption: "TLS",
    senderName: "MyITCompany CMS Admin",
    senderEmail: "no-reply@myitcompany.com",
    autoReplyToggle: true,
    autoReplyTemplate: "Hello [Name],\n\nThank you for reaching out to MyITCompany. We have received your inquiry regarding \"[Subject]\" and our engineering team will get back to you within 24 hours.\n\nBest regards,\nMyITCompany Support",
    emailLogs: [],
    emailQueue: []
  },
  seoSettings: {
    websiteTitle: "MyITCompany - Managed IT Services & Custom Solutions",
    metaTitle: "Enterprise-Grade IT Solutions, Cloud Migrations & Cybersecurity Audits",
    metaDescription: "MyITCompany provides premium managed IT infrastructure, DevOps consulting, customized software development.",
    keywords: "Managed IT Services, Cloud Architecture, DevOps Consulting, Zero Trust Security",
    canonicalUrl: "https://siteandmarketing.com/",
    robotsTxt: "User-agent: *\nDisallow: /admin/",
    xmlSitemap: "https://siteandmarketing.com/sitemap.xml",
    ogImage: "https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1200&q=80",
    twitterCard: "summary_large_image",
    schemaMarkup: "{}",
    googleAnalyticsId: "",
    searchConsoleVerification: "",
    bingWebmasterVerification: "",
    indexActive: true,
    followActive: true,
    breadcrumbActive: true,
    jsonLdActive: true
  },
  websiteSettings: {
    websiteName: "MyITCompany",
    websiteDescription: "Premium IT consulting, remote infrastructure monitoring, cloud migrations, and full-stack software development.",
    logoUrl: "https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=150&q=80",
    faviconUrl: "https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=48&q=80",
    primaryColor: "#6366f1",
    secondaryColor: "#8b5cf6",
    footerText: "© 2026 MyITCompany. All rights reserved.",
    socialLinks: {
      linkedin: "https://linkedin.com",
      twitter: "https://twitter",
      github: "https://github",
      facebook: "https://facebook"
    },
    businessAddress: "Islamabad, Pakistan",
    businessPhone: "+92 XXX XXX XXXX",
    businessEmail: "hello@myitcompany.com",
    googleMapsEmbedUrl: "",
    timezone: "Asia/Karachi",
    language: "en"
  },
  mediaLibrary: [],
  activityLogs: [],
  backups: [],
  notifications: [],
  services: [
    {
      id: "svc-1",
      title: "Custom Software Development",
      slug: "custom-software-development",
      description: "We build robust, scalable, and maintainable software solutions tailored to your business workflows.",
      icon: "fa-solid fa-code",
      image_url: "https://images.unsplash.com/photo-1461749280684-dccba630e2f6?auto=format&fit=crop&w=800&q=80",
      status: "Published"
    },
    {
      id: "svc-2",
      title: "Cloud Architecture & DevOps",
      slug: "cloud-architecture-devops",
      description: "Design and deploy scalable cloud infrastructure on AWS, Azure or GCP with full CI/CD automation.",
      icon: "fa-solid fa-cloud",
      image_url: "https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80",
      status: "Published"
    },
    {
      id: "svc-3",
      title: "Cybersecurity & Compliance",
      slug: "cybersecurity-compliance",
      description: "Protect your business with advanced threat detection, security audits, and zero-trust architecture.",
      icon: "fa-solid fa-shield-halved",
      image_url: "https://images.unsplash.com/photo-1563013544-824ae1b704d3?auto=format&fit=crop&w=800&q=80",
      status: "Published"
    }
  ],
  projects: [
    {
      id: "proj-1",
      title: "Enterprise ERP Deployment",
      slug: "enterprise-erp-deployment",
      description: "Full-scale ERP migration and cloud deployment for a manufacturing client with 500+ users.",
      category: "Software Dev",
      image_url: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80",
      client: "ManufactureCo Ltd",
      year: "2025",
      tags: "ERP, Cloud, Migration",
      status: "Published"
    },
    {
      id: "proj-2",
      title: "SaaS Analytics Dashboard",
      slug: "saas-analytics-dashboard",
      description: "Modern real-time analytics dashboard with interactive data visualizations and AI-powered insights.",
      category: "UI / UX Design",
      image_url: "https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&w=800&q=80",
      client: "DataStream Inc",
      year: "2025",
      tags: "SaaS, Dashboard, Analytics",
      status: "Published"
    }
  ],
  team: [
    {
      id: "tm-1",
      name: "Michael Jenkins",
      designation: "Founder & CEO",
      bio: "Serial entrepreneur with 15+ years experience building technology companies.",
      image_url: "https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=600&q=80",
      linkedin_url: "https://linkedin.com",
      github_url: "",
      twitter_url: "",
      sort_order: 1,
      status: "Published"
    },
    {
      id: "tm-2",
      name: "William Anderson",
      designation: "CTO & Principal Designer",
      bio: "Full-stack architect specializing in distributed systems and cloud-native solutions.",
      image_url: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=600&q=80",
      linkedin_url: "https://linkedin.com",
      github_url: "https://github.com",
      twitter_url: "",
      sort_order: 2,
      status: "Published"
    },
    {
      id: "tm-3",
      name: "David Daniel",
      designation: "Head of Cybersecurity",
      bio: "Certified CISSP with expertise in zero-trust security and compliance frameworks.",
      image_url: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=600&q=80",
      linkedin_url: "",
      github_url: "",
      twitter_url: "https://twitter.com",
      sort_order: 3,
      status: "Published"
    }
  ],
  testimonials: [
    {
      id: "tst-1",
      client_name: "Sarah Jenkins",
      company: "Director of Infrastructure, TechCorp",
      review: "The team delivered beyond our expectations. Cloud infrastructure scalability improved by 250%, and security threats dropped to virtually zero.",
      rating: 5,
      image_url: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80",
      status: "Published"
    },
    {
      id: "tst-2",
      client_name: "Marcus Vance",
      company: "VP of Product, CloudScale",
      review: "Their IT consultation streamlined our development workflow. We reduced monthly hosting overheads by 40%.",
      rating: 5,
      image_url: "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=150&q=80",
      status: "Published"
    }
  ],
  faqs: [
    {
      id: "faq-1",
      question: "What technologies do you specialize in?",
      answer: "We specialize in PHP, Python, Node.js, React, Vue.js, cloud platforms (AWS, GCP, Azure), Docker, Kubernetes, and MySQL/PostgreSQL.",
      sort_order: 1,
      status: "Published"
    },
    {
      id: "faq-2",
      question: "How long does a typical project take?",
      answer: "Project timelines vary based on scope. A typical web app takes 4-12 weeks. Enterprise systems can take 3-6 months. We provide detailed timelines after initial discovery.",
      sort_order: 2,
      status: "Published"
    },
    {
      id: "faq-3",
      question: "Do you offer ongoing support after launch?",
      answer: "Yes, we offer tiered maintenance and support plans including 24/7 monitoring, monthly security patches, performance optimization, and dedicated account management.",
      sort_order: 3,
      status: "Published"
    }
  ]
};

export const Store = {
  key: "myitcompany_cms_store",
  _cache: {},
  _initialized: false,

  async init() {
    if (this._initialized) return;
    try {
      const response = await fetch("api.php?action=get_all");
      const data = await response.json();
      
      this._cache = data;

      // Seed baseline elements if site settings have not been initialized yet
      if (!this._cache.websiteSettings) {
        await this.seedDefaults();
      }
      this._initialized = true;
    } catch (e) {
      console.error("MySQL Sync failed. Restoring LocalStorage caches fallback.", e);
      if (!localStorage.getItem(this.key)) {
        localStorage.setItem(this.key, JSON.stringify(INITIAL_DATA));
      }
      this._cache = JSON.parse(localStorage.getItem(this.key));
      this._initialized = true;
    }
  },

  async seedDefaults() {
    this._cache = { ...INITIAL_DATA };
    // Table-backed keys are managed by their own MySQL tables, not site_settings JSON
    const tableBacked = ['blogs', 'contacts', 'users', 'services', 'projects', 'team', 'testimonials', 'faqs'];
    for (const k in INITIAL_DATA) {
      if (!tableBacked.includes(k)) {
        try {
          await fetch("api.php?action=save_state", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ key: k, value: INITIAL_DATA[k] })
          });
        } catch (err) {
          console.error("Failed seeding default key " + k, err);
        }
      }
    }
  },

  getAll() {
    return this._cache;
  },

  get(subKey) {
    return this._cache[subKey];
  },

  set(subKey, value) {
    this._cache[subKey] = value;
    
    // Dispatch custom event to notify components of state changes
    window.dispatchEvent(new CustomEvent("storeUpdated", { detail: { key: subKey, value } }));

    // Async write to MySQL database in background
    fetch("api.php?action=save_state", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ key: subKey, value: value })
    }).catch(err => {
      console.error("MySQL async write failure:", err);
      // Save local backup in localStorage
      localStorage.setItem(this.key, JSON.stringify(this._cache));
    });
  },

  updateItem(subKey, itemId, updatedFields) {
    const arr = this.get(subKey);
    if (!Array.isArray(arr)) return;
    const index = arr.findIndex(item => item.id === itemId);
    if (index !== -1) {
      arr[index] = { ...arr[index], ...updatedFields };
      this.set(subKey, arr);
      this.logActivity(`Modified item ${itemId} in ${subKey}`);
      return arr[index];
    }
  },

  insertItem(subKey, newItem) {
    const arr = this.get(subKey) || [];
    if (!Array.isArray(arr)) return;
    
    if (!newItem.id) {
      newItem.id = subKey.substring(0, 3) + "-" + Math.random().toString(36).substr(2, 9);
    }
    
    arr.unshift(newItem);
    this.set(subKey, arr);
    this.logActivity(`Created new item in ${subKey}`);
    return newItem;
  },

  deleteItem(subKey, itemId) {
    const arr = this.get(subKey);
    if (!Array.isArray(arr)) return;
    const filtered = arr.filter(item => item.id !== itemId);
    this.set(subKey, filtered);
    this.logActivity(`Deleted item ${itemId} from ${subKey}`);
    return filtered;
  },

  logActivity(action, module = "System") {
    const logs = this.get("activityLogs") || [];
    const newLog = {
      id: "act-" + Date.now(),
      user: "Jack Devlin",
      action,
      module,
      ipAddress: "127.0.0.1",
      date: new Date().toISOString()
    };
    logs.unshift(newLog);
    if (logs.length > 50) logs.pop();
    this.set("activityLogs", logs);
  },

  reset() {
    this._cache = { ...INITIAL_DATA };
    this.seedDefaults().then(() => {
      this.logActivity("Database reset to factory settings");
      window.location.reload();
    });
  }
};
