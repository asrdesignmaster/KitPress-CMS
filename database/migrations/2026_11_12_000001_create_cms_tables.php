<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('file_name');
            $table->string('disk')->default('public');
            $table->string('mime_type')->default('image/webp');
            $table->string('path');
            $table->string('webp_path')->nullable();
            $table->json('sizes')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('alt_text')->nullable();
            $table->text('caption')->nullable();
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', ['post', 'page'])->default('post');
            $table->enum('editor_type', ['classic', 'builder'])->default('classic');
            $table->longText('classic_content')->nullable();
            $table->json('builder_json')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->string('featured_image')->nullable();
            $table->foreignId('featured_image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->text('excerpt')->nullable();
            $table->timestamp('published_at')->nullable();

            // SEO Suite Meta fields
            $table->string('seo_focus_keyword')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_og_title')->nullable();
            $table->text('seo_og_description')->nullable();
            $table->string('seo_og_image')->nullable();
            $table->boolean('seo_robots_index')->default(true);
            $table->boolean('seo_robots_follow')->default(true);

            $table->timestamps();
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['post_id', 'tag_id']);
        });

        Schema::create('post_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->enum('editor_type', ['classic', 'builder'])->default('classic');
            $table->longText('classic_content')->nullable();
            $table->json('builder_json')->nullable();
            $table->string('summary')->nullable();
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('post_revisions');
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('media');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('categories');
    }
};

