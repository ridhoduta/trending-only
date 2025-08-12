<?php

use App\Models\KategoriModel;
use App\Models\ArtikelModel;

if (!function_exists('generateSwitchLanguageUrl')) {
    function generateSwitchLanguageUrl($currentLang, $currentCategorySlug = null, $currentArticleSlug = null)
    {
        $categoryModel = new KategoriModel();
        $articleModel = new ArtikelModel();

        $newLang = ($currentLang === 'id') ? 'en' : 'id';
        $switchUrl = base_url($newLang);

        if ($currentCategorySlug && $currentArticleSlug) {
            $category = $categoryModel->where("slug_{$currentLang}", $currentCategorySlug)->first();
            $article = $articleModel->where("slug_{$currentLang}", $currentArticleSlug)->first();
            if ($category && $article) {
                $newCategorySlug = $category["slug_{$newLang}"];
                $newArticleSlug = $article["slug_{$newLang}"];
                return base_url("$newLang/$newCategorySlug/$newArticleSlug");
            }
        } elseif ($currentCategorySlug) {
            $category = $categoryModel->where("slug_{$currentLang}", $currentCategorySlug)->first();
            if ($category) {
                $newCategorySlug = $category["slug_{$newLang}"];
                return base_url("$newLang/$newCategorySlug");
            }
        }

        return base_url($newLang);
    }
}
