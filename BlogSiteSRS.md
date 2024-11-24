### Software Requirement Specification (SRS) for Simple Personal Blog

---

#### 1. **Introduction**

   - **Purpose**: This SRS outlines the development requirements for a simple personal blog. The blog will feature article management by an author, basic routing, authentication, and CRUD functionality.
   - **Scope**: The system will consist of a Homepage, Article Page, and an About page, accessible to all users. An author login will allow access to manage articles, categories, and tags.

---

#### 2. **Functional Requirements**

   **2.1 User Roles and Access Control**
   
   - **Guest Users**: 

     - Access the homepage with a list of articles.
     - Access individual articles via the article page.
     - Access static informational pages like "About Me."

   - **Author**:

     - Log in to access the admin interface (no registration).
     - Create, update, and delete articles, categories, and tags.

   **2.2 Pages and Features**

   - **Homepage (Article Listing)**:

     - Display a list of published articles.
     - Each article preview shows the title, excerpt, category, and tags.
     - Link each article to its respective article page.

   - **Article Page**:

     - Display the full article, including title, full text, image (if uploaded), category, and tags.

   - **About Page**:

     - Static page with information about the author.

   - **Login Page**:

     - Author login using Laravel Breeze or Laravel UI for authentication.

   - **Admin Interface**:

     - **Article Management**: Create, update, delete articles with a title, full text, optional image, category selection, and multiple tags.
     - **Category Management**: Create, update, and delete categories (each article has only one category).
     - **Tag Management**: Create, update, and delete tags (articles may have multiple tags).

---

#### 3. **Non-Functional Requirements**

   - **Usability**: Simple, minimal design without complex styling; focus on functionality.
   - **Reliability**: Ensure CRUD operations are handled with error handling and validation.
   - **Performance**: Use Eager Loading for optimized database queries.
   - **Security**: Basic authentication for authors to protect the admin interface.

---

#### 4. **Database Design**

   **4.1 Database Tables**

   - **Users**: 

     - `id` , `name` , `email` , `password` , `created_at` , `updated_at`

   - **Articles**:

     - `id` , `title` , `text` , `image_path` , `category_id` , `created_at` , `updated_at`

   - **Categories**:

     - `id` , `name` , `created_at` , `updated_at`

   - **Tags**:

     - `id` , `name` , `created_at` , `updated_at`

   - **Article_Tag (pivot table)**:

     - `article_id` , `tag_id`

---

#### 5. **Functional Details and Implementation Plan**

   **5.1 Routing and Controllers**

   - Set up routes with appropriate naming conventions.
   - Implement route groups to separate public and author-only routes.
   - Define `Route::view()` for the static "About" page.
   - Configure resourceful routes for articles, categories, and tags.

   **5.2 Blade Views and Templates**

   - **Homepage**: Display list of articles with Blade loops and conditional statements.
   - **Article Page**: Display article details with Blade components for dynamic content.
   - **Admin Interface**: Use `@extends` , `@section` , and `@yield` for page layout and form inclusion.
   - **Components**: Create reusable components for elements like article cards.

   **5.3 Authentication and Authorization**

   - Set up authentication using Laravel Breeze or Laravel UI.
   - Apply middleware for routes to ensure only logged-in authors can access the admin interface.

   **5.4 Database Management**

   - **Migrations**: Create migrations for all database tables.
   - **Relationships**: Define Eloquent relationships (belongsTo, hasMany, belongsToMany).
   - **Eager Loading**: Optimize queries to avoid N+1 query problems in article lists.

   **5.5 Full CRUD Operations**

   - **CRUD for Articles**:

     - Implement full CRUD operations with validation.
     - Enable file uploads for article images and manage storage.

   
   - **CRUD for Categories and Tags**:

     - Implement CRUD operations for categories (one-to-many relationship) and tags (many-to-many relationship).

---

#### 6. **Testing Requirements**

   - **Functional Testing**:

     - Test routing, CRUD operations, and data validation for all entities.

   - **Authentication Testing**:

     - Verify that only authenticated users can access the author interface.

   - **Database Testing**:

     - Ensure data integrity for articles, categories, and tags, especially for many-to-many relationships.

   - **Image Upload Testing**:

     - Test image upload functionality, ensuring uploaded files are stored correctly.

---

This SRS provides a detailed guide for developing and testing a Laravel-based personal blog system with essential features for article management, routing, and authentication.
