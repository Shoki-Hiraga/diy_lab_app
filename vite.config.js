import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input:
            [
                'resources/css/app.css',
                'resources/css/common/header.css',
                'resources/css/common/breadcrumbs.css',
                'resources/css/common/posts-index.base.css',
                'resources/css/common/posts-index.breakpoints.css',
                'resources/css/common/post-card.css',
                'resources/css/common/post-card.breakpoints.css',
                'resources/css/common/posts-floating.base.css',
                'resources/css/common/posts-floating.breakpoints.css',
                'resources/css/common/posts-comment.base.css',
                'resources/css/common/posts-comment.breakpoints.css',
                'resources/css/common/search.css',
                'resources/css/common/cta-card.css',
                'resources/css/users/login.css',
                'resources/css/users/posts-form.base.css',
                'resources/css/users/posts-form.breakpoints.css',
                'resources/css/users/users.css',
                'resources/css/users/dashboard.css',
                'resources/js/comments.js',
                'resources/css/common/legal.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
});
