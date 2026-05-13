<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';

require_login();

$notice = '';
$error = '';
$editingProject = null;
$projects = [];
$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save_project') {
    verify_csrf();

    $projectId = (int) ($_POST['project_id'] ?? 0);
    $payload = [
        ':title' => clean_string($_POST['title'] ?? ''),
        ':category' => clean_string($_POST['category'] ?? 'frontend'),
        ':summary' => clean_string($_POST['summary'] ?? ''),
        ':technologies' => clean_string($_POST['technologies'] ?? ''),
        ':project_url' => clean_string($_POST['project_url'] ?? '#'),
        ':display_order' => (int) ($_POST['display_order'] ?? 0),
        ':is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        ':is_active' => isset($_POST['is_active']) ? 1 : 0,
    ];

    if ($payload[':title'] === '' || $payload[':summary'] === '') {
        $error = 'Title and summary are required.';
    } else {
        try {
            if ($projectId > 0) {
                $payload[':id'] = $projectId;
                $statement = db()->prepare(
                    'UPDATE projects
                     SET title = :title, category = :category, summary = :summary, technologies = :technologies,
                         project_url = :project_url, display_order = :display_order, is_featured = :is_featured,
                         is_active = :is_active, updated_at = CURRENT_TIMESTAMP
                     WHERE id = :id'
                );
                $statement->execute($payload);
                $notice = 'Project updated successfully.';
            } else {
                $statement = db()->prepare(
                    'INSERT INTO projects (title, category, summary, technologies, project_url, display_order, is_featured, is_active)
                     VALUES (:title, :category, :summary, :technologies, :project_url, :display_order, :is_featured, :is_active)'
                );
                $statement->execute($payload);
                $notice = 'Project added successfully.';
            }
        } catch (Throwable $exception) {
            $error = 'Project could not be saved. Check the database connection.';
        }
    }
}

try {
    if (isset($_GET['edit'])) {
        $statement = db()->prepare('SELECT * FROM projects WHERE id = :id LIMIT 1');
        $statement->execute([':id' => (int) $_GET['edit']]);
        $editingProject = $statement->fetch() ?: null;
    }

    $projects = db()->query('SELECT * FROM projects ORDER BY display_order ASC, created_at DESC')->fetchAll();
    $messages = db()->query('SELECT * FROM contacts ORDER BY created_at DESC LIMIT 20')->fetchAll();
} catch (Throwable $exception) {
    $error = $error !== '' ? $error : 'Dashboard data could not be loaded. Import database/portfolio.sql first.';
}

$formProject = $editingProject ?: [
    'id' => '',
    'title' => '',
    'category' => 'frontend',
    'summary' => '',
    'technologies' => '',
    'project_url' => '#',
    'display_order' => 0,
    'is_featured' => 0,
    'is_active' => 1,
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | Emirhan Demircan</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="./admin.css">
</head>
<body>
  <main class="admin-shell">
    <header class="admin-topbar">
      <div>
        <p class="section-subtitle">Dashboard</p>
        <h1>Welcome, <?= e($_SESSION['admin_username'] ?? 'admin') ?></h1>
        <p class="admin-muted">Manage projects and review saved contact messages from MySQL.</p>
      </div>
      <nav class="admin-actions" aria-label="Admin actions">
        <a href="../index.html" class="btn btn-outline">View Site</a>
        <a href="logout.php" class="btn btn-primary">Logout</a>
      </nav>
    </header>

    <?php if ($notice !== ''): ?><p class="admin-alert success"><?= e($notice) ?></p><?php endif; ?>
    <?php if ($error !== ''): ?><p class="admin-alert"><?= e($error) ?></p><?php endif; ?>

    <section class="admin-grid">
      <article class="admin-card">
        <h2><?= $editingProject ? 'Edit Project' : 'Add New Project' ?></h2>
        <form method="post" class="admin-form">
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="action" value="save_project">
          <input type="hidden" name="project_id" value="<?= e((string) $formProject['id']) ?>">

          <label><span>Title</span><input type="text" name="title" required value="<?= e((string) $formProject['title']) ?>"></label>
          <label>
            <span>Category</span>
            <select name="category" required>
              <?php foreach (['frontend', 'javascript', 'fullstack'] as $category): ?>
                <option value="<?= e($category) ?>" <?= $formProject['category'] === $category ? 'selected' : '' ?>><?= e(ucfirst($category)) ?></option>
              <?php endforeach; ?>
            </select>
          </label>
          <label><span>Summary</span><textarea name="summary" required><?= e((string) $formProject['summary']) ?></textarea></label>
          <label><span>Technologies (comma separated)</span><input type="text" name="technologies" value="<?= e((string) $formProject['technologies']) ?>"></label>
          <label><span>Project URL</span><input type="url" name="project_url" value="<?= e((string) $formProject['project_url']) ?>"></label>
          <label><span>Display Order</span><input type="number" name="display_order" value="<?= e((string) $formProject['display_order']) ?>"></label>

          <div class="admin-checks">
            <label><input type="checkbox" name="is_featured" <?= (int) $formProject['is_featured'] === 1 ? 'checked' : '' ?>> Featured</label>
            <label><input type="checkbox" name="is_active" <?= (int) $formProject['is_active'] === 1 ? 'checked' : '' ?>> Active</label>
          </div>

          <button type="submit" class="btn-submit"><?= $editingProject ? 'Save Changes' : 'Add Project' ?></button>
          <?php if ($editingProject): ?><a class="admin-link" href="dashboard.php">Cancel edit</a><?php endif; ?>
        </form>
      </article>

      <article class="admin-card">
        <h2>Projects</h2>
        <div class="admin-table-wrap">
          <table class="admin-table">
            <thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
              <?php foreach ($projects as $project): ?>
                <tr>
                  <td><?= e($project['title']) ?></td>
                  <td><?= e($project['category']) ?></td>
                  <td><?= (int) $project['is_active'] === 1 ? 'Active' : 'Hidden' ?></td>
                  <td><a href="dashboard.php?edit=<?= e((string) $project['id']) ?>">Edit</a></td>
                </tr>
              <?php endforeach; ?>
              <?php if ($projects === []): ?><tr><td colspan="4">No projects found.</td></tr><?php endif; ?>
            </tbody>
          </table>
        </div>
      </article>
    </section>

    <section class="admin-card">
      <h2>Recent Contact Messages</h2>
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead><tr><th>Name</th><th>Email</th><th>Message</th><th>Date</th></tr></thead>
          <tbody>
            <?php foreach ($messages as $message): ?>
              <tr>
                <td><?= e($message['name']) ?></td>
                <td><a href="mailto:<?= e($message['email']) ?>"><?= e($message['email']) ?></a></td>
                <td><?= e($message['message']) ?></td>
                <td><?= e($message['created_at']) ?></td>
              </tr>
            <?php endforeach; ?>
            <?php if ($messages === []): ?><tr><td colspan="4">No messages saved yet.</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</body>
</html>
