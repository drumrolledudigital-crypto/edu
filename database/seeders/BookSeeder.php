<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = Subject::where('status', 'active')->get();

        $books = [
            ['title' => 'Algebra Fundamentals', 'subject' => 'Mathematics', 'description' => 'Covers linear equations, quadratic expressions, and basic algebraic operations for Classes 5-8.'],
            ['title' => 'Geometry for Beginners', 'subject' => 'Mathematics', 'description' => 'Introduction to shapes, angles, circles, and theorems with practical examples.'],
            ['title' => 'Arithmetic Workbook', 'subject' => 'Mathematics', 'description' => 'Practice problems for fractions, decimals, percentages, and ratios.'],
            ['title' => 'Introduction to Physics', 'subject' => 'Science', 'description' => 'Basic concepts of motion, force, energy, and simple machines for young learners.'],
            ['title' => 'Chemistry Basics', 'subject' => 'Science', 'description' => 'Elements, compounds, mixtures, and chemical reactions explained simply.'],
            ['title' => 'Biology Explorers', 'subject' => 'Science', 'description' => 'Plants, animals, human body, and ecosystems for Classes 3-8.'],
            ['title' => 'English Grammar Guide', 'subject' => 'English', 'description' => 'Comprehensive grammar covering parts of speech, tenses, and sentence structure.'],
            ['title' => 'Creative Writing', 'subject' => 'English', 'description' => 'Essay writing, story writing, and letter writing techniques with examples.'],
            ['title' => 'Reading Comprehension', 'subject' => 'English', 'description' => 'Practice passages with questions to improve reading skills for Classes 1-8.'],
            ['title' => 'Hindi Vyakaran', 'subject' => 'Hindi', 'description' => 'Complete Hindi grammar including sandhi, samas, and alankar for school curriculum.'],
            ['title' => 'Hindi Nibandh Sangrah', 'subject' => 'Hindi', 'description' => 'Collection of essays on various topics for Classes 1-8.'],
            ['title' => 'World History for Kids', 'subject' => 'Social Studies', 'description' => 'Ancient civilizations, medieval period, and modern history made fun.'],
            ['title' => 'Geography Explorer', 'subject' => 'Social Studies', 'description' => 'Maps, continents, climate, and environmental studies for Classes 4-8.'],
            ['title' => 'Civics & Governance', 'subject' => 'Social Studies', 'description' => 'Understanding democracy, constitution, and citizen rights for young minds.'],
            ['title' => 'Scratch Programming', 'subject' => 'Computer Science', 'description' => 'Learn programming fundamentals using Scratch with fun projects and animations.'],
            ['title' => 'Digital Literacy', 'subject' => 'Computer Science', 'description' => 'Computer basics, internet safety, and productivity tools for students.'],
            ['title' => 'Logical Reasoning', 'subject' => 'Mental Ability', 'description' => 'Pattern recognition, analogies, puzzles, and critical thinking exercises.'],
            ['title' => 'Olympiad Prep Guide', 'subject' => 'Mental Ability', 'description' => 'Preparation material for mathematics and science olympiads for Classes 2-8.'],
        ];

        $prices = [12.99, 14.99, 9.99, 15.99, 13.99, 11.99, 10.99, 9.99, 8.99, 8.99, 7.99, 14.99, 12.99, 11.99, 16.99, 10.99, 9.99, 13.99];

        foreach ($books as $index => $data) {
            $subject = $subjects->firstWhere('name', $data['subject']);
            if (!$subject) continue;

            Book::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'subject_id' => $subject->id,
                    'title' => $data['title'],
                    'short_description' => $data['description'],
                    'cover_image' => null,
                    'pdf_file' => null,
                    'status' => 'active',
                    'price' => $prices[$index] ?? 9.99,
                ]
            );
        }
    }
}
