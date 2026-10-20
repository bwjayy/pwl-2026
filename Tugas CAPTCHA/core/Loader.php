<?php

class Loader
{
    public function view(string $viewPath, array $data = []): void
    {
        extract($data);

        $fullPath = __DIR__ . '/../' . ltrim($viewPath, '/');
        if (!file_exists($fullPath)) {
            http_response_code(500);
            echo "View file not found: {$viewPath}";
            return;
        }

        require $fullPath;
    }
}
