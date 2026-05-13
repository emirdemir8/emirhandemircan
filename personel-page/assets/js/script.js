const header = document.querySelector('[data-header]');
const navToggleBtn = document.querySelector('[data-nav-toggle-btn]');
const navbarLinks = document.querySelectorAll('[data-nav-link]');
const backTopBtn = document.querySelector('[data-back-to-top]');
const themeToggle = document.querySelector('[data-theme-toggle]');
const projectList = document.querySelector('[data-project-list]');
const projectStatus = document.querySelector('[data-project-status]');
const filterButtons = document.querySelectorAll('[data-filter]');
const contactForm = document.querySelector('[data-contact-form]');
const formMessage = document.querySelector('[data-form-message]');

const fallbackProjects = [
  {
    title: 'Clipboard Landing Page',
    category: 'frontend',
    summary: 'Responsive landing page with semantic HTML, Flexbox/Grid layout, and consistent brand styling.',
    technologies: ['HTML5', 'CSS3', 'Responsive Design'],
    project_url: '../Landing%20Page/index.html'
  },
  {
    title: 'News Homepage',
    category: 'javascript',
    summary: 'Interactive news page using DOM events for the mobile menu and layout behavior.',
    technologies: ['HTML5', 'CSS3', 'JavaScript'],
    project_url: '../media/news%20page/index.html'
  },
  {
    title: 'Portfolio Admin System',
    category: 'fullstack',
    summary: 'PHP/MySQL admin dashboard with sessions, cookies, project CRUD, and saved contact messages.',
    technologies: ['PHP', 'MySQL', 'Sessions', 'AJAX'],
    project_url: './admin/login.php'
  }
];

let projects = [];
let activeFilter = 'all';

function setMenu(open) {
  header.classList.toggle('nav-active', open);
  navToggleBtn.classList.toggle('active', open);
  navToggleBtn.setAttribute('aria-expanded', String(open));
  document.body.classList.toggle('menu-open', open);
}

navToggleBtn?.addEventListener('click', () => {
  setMenu(!header.classList.contains('nav-active'));
});

navbarLinks.forEach((link) => {
  link.addEventListener('click', () => setMenu(false));
});

window.addEventListener('scroll', () => {
  const isScrolled = window.scrollY >= 80;
  header?.classList.toggle('active', isScrolled);
});

function applySavedTheme() {
  const cookieTheme = document.cookie
    .split('; ')
    .find((row) => row.startsWith('portfolio_theme='))
    ?.split('=')[1];
  const savedTheme = cookieTheme || localStorage.getItem('portfolio_theme');

  if (savedTheme === 'light') {
    document.body.classList.add('light-theme');
  }
}

function toggleTheme() {
  document.body.classList.toggle('light-theme');
  const theme = document.body.classList.contains('light-theme') ? 'light' : 'dark';
  localStorage.setItem('portfolio_theme', theme);
  document.cookie = `portfolio_theme=${theme}; max-age=31536000; path=/; samesite=lax`;
}

applySavedTheme();
themeToggle?.addEventListener('click', toggleTheme);

function normalizeProject(project) {
  return {
    title: project.title || 'Untitled Project',
    category: (project.category || 'frontend').toLowerCase(),
    summary: project.summary || project.description || 'Project details will be added soon.',
    technologies: Array.isArray(project.technologies)
      ? project.technologies
      : String(project.technologies || '')
          .split(',')
          .map((item) => item.trim())
          .filter(Boolean),
    project_url: project.project_url || project.url || '#'
  };
}

function renderProjects() {
  if (!projectList) return;

  const filteredProjects = projects.filter((project) => activeFilter === 'all' || project.category === activeFilter);
  projectList.innerHTML = '';

  if (!filteredProjects.length) {
    projectStatus.textContent = 'No projects found for this filter.';
    return;
  }

  projectStatus.textContent = `${filteredProjects.length} project(s) displayed.`;

  filteredProjects.forEach((project) => {
    const item = document.createElement('li');
    const card = document.createElement('article');
    const category = document.createElement('p');
    const title = document.createElement('h3');
    const summary = document.createElement('p');
    const tags = document.createElement('div');
    const link = document.createElement('a');
    const linkText = document.createElement('span');
    const icon = document.createElement('ion-icon');

    card.className = 'project-card';
    card.dataset.category = project.category;
    category.className = 'card-subtitle';
    category.textContent = project.category;
    title.className = 'h3 card-title';
    title.textContent = project.title;
    summary.textContent = project.summary;
    tags.className = 'project-tags';
    tags.setAttribute('aria-label', 'Technologies');

    project.technologies.forEach((tech) => {
      const tag = document.createElement('span');
      tag.textContent = tech;
      tags.appendChild(tag);
    });

    link.className = 'btn-link';
    link.href = project.project_url;
    link.target = '_blank';
    link.rel = 'noreferrer';
    linkText.textContent = 'View Project';
    icon.setAttribute('name', 'arrow-forward');
    icon.setAttribute('aria-hidden', 'true');
    link.append(linkText, icon);
    card.append(category, title, summary, tags, link);
    item.appendChild(card);
    projectList.appendChild(item);
  });
}

async function loadProjects() {
  if (!projectList) return;

  try {
    const response = await fetch('./api/projects.php', { headers: { Accept: 'application/json' } });
    if (!response.ok) throw new Error('Project API is not available yet.');

    const payload = await response.json();
    const apiProjects = Array.isArray(payload.projects) ? payload.projects : [];
    projects = apiProjects.length ? apiProjects.map(normalizeProject) : fallbackProjects.map(normalizeProject);
    projectStatus.textContent = apiProjects.length ? 'Projects loaded from MySQL.' : 'Demo projects loaded. Import SQL to use database records.';
  } catch (error) {
    projects = fallbackProjects.map(normalizeProject);
    projectStatus.textContent = 'Demo projects loaded. Configure PHP/MySQL to enable dynamic database content.';
  }

  renderProjects();
}

filterButtons.forEach((button) => {
  button.addEventListener('click', () => {
    activeFilter = button.dataset.filter;
    filterButtons.forEach((item) => item.classList.toggle('active', item === button));
    renderProjects();
  });
});

function setFieldState(field, isValid) {
  field.classList.toggle('invalid', !isValid);
}

function validateContactForm(form) {
  const fields = {
    name: form.elements.name,
    email: form.elements.email,
    message: form.elements.message
  };
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const errors = [];

  const validName = fields.name.value.trim().length >= 2;
  const validEmail = emailPattern.test(fields.email.value.trim());
  const validMessage = fields.message.value.trim().length >= 10;

  setFieldState(fields.name, validName);
  setFieldState(fields.email, validEmail);
  setFieldState(fields.message, validMessage);

  if (!validName) errors.push('Name must be at least 2 characters.');
  if (!validEmail) errors.push('Please enter a valid email address.');
  if (!validMessage) errors.push('Message must be at least 10 characters.');

  return errors;
}

contactForm?.addEventListener('submit', async (event) => {
  event.preventDefault();
  const errors = validateContactForm(contactForm);

  formMessage.className = 'form-message';
  if (errors.length) {
    formMessage.textContent = errors[0];
    formMessage.classList.add('error');
    return;
  }

  formMessage.textContent = 'Sending...';

  try {
    const response = await fetch(contactForm.action, {
      method: 'POST',
      body: new FormData(contactForm),
      headers: { Accept: 'application/json' }
    });
    const payload = await response.json();

    if (!response.ok || !payload.success) {
      throw new Error(payload.message || 'Message could not be saved.');
    }

    formMessage.textContent = payload.message || 'Message saved successfully.';
    formMessage.classList.add('success');
    contactForm.reset();
  } catch (error) {
    formMessage.textContent = `${error.message} Check database configuration if you are running locally.`;
    formMessage.classList.add('error');
  }
});

loadProjects();
