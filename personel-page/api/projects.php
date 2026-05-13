<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

try {
    $statement = db()->query(
        'SELECT id, title, category, summary, technologies, project_url, display_order, is_featured
         FROM projects
         WHERE is_active = 1
         ORDER BY display_order ASC, created_at DESC'
    );

    $projects = array_map(static function (array $project): array {
        $project['id'] = (int) $project['id'];
        $project['display_order'] = (int) $project['display_order'];
        $project['is_featured'] = (bool) $project['is_featured'];
        $project['technologies'] = array_values(array_filter(array_map('trim', explode(',', (string) $project['technologies']))));
        return $project;
    }, $statement->fetchAll());

    json_response([
        'success' => true,
        'projects' => $projects,
    ]);
} catch (Throwable $exception) {
    json_response([
        'success' => false,
        'message' => 'Projects could not be loaded. Import database/portfolio.sql and check database credentials.',
        'projects' => [],
    ], 503);
}
