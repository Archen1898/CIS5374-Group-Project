<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 2rem;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="mb-4">User Posts</h1>

    <a href="http://127.0.0.1:8000/api/submit_post">
        <button class="btn btn-primary mb-4">Create New Post</button>
    </a>

    <div id="posts-container">
        <div class="text-muted">Loading posts...</div>
    </div>
</div>

<script>
    const apiUrl = 'http://127.0.0.1:8000/api/post/index';
    const commentUrl = 'http://127.0.0.1:8000/api/comment/create';
    const userId = localStorage.getItem('user_id');

    function createCommentList(comments) {
        if (comments.length === 0) {
            return `<li class="list-group-item text-muted">No comments found.</li>`;
        }

        return comments.map(comment => `
            <li class="list-group-item">${comment.comment}</li>
        `).join('');
    }

    async function fetchComments(postId) {
        try {
            const res = await fetch(`http://127.0.0.1:8000/api/comment/index/${postId}`);
            const result = await res.json();

            if (result.status === 200 && Array.isArray(result.data)) {
                return result.data;
            } else {
                console.warn(`No comments for post ${postId}`);
                return [];
            }
        } catch (err) {
            console.error(`Error fetching comments for post ${postId}:`, err);
            return [];
        }
    }

    async function createPostCard(post) {
        const comments = await fetchComments(post.id);
        const commentHTML = createCommentList(comments);

        return `
            <div class="card mb-4" data-post-id="${post.id}">
                <div class="card-body">
                    <h4 class="card-title">${post.title}</h4>
                    <p class="card-text">${post.content}</p>
                </div>
                <ul class="list-group list-group-flush">
                    ${commentHTML}
                </ul>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control comment-input" placeholder="Write a comment...">
                        <button class="btn btn-outline-secondary submit-comment-btn" type="button">Submit</button>
                    </div>
                    <small class="text-success comment-success" style="display: none;">Comment submitted!</small>
                    <small class="text-danger comment-error" style="display: none;">Failed to submit comment.</small>
                </div>
            </div>
        `;
    }

    async function loadPosts() {
        const container = document.getElementById('posts-container');
        container.innerHTML = '';

        try {
            const response = await fetch(apiUrl);
            const text = await response.text();
            const result = JSON.parse(text);

            if (result.status === 200 && Array.isArray(result.data)) {
                if (result.data.length === 0) {
                    container.innerHTML = '<p>No posts found.</p>';
                    return;
                }

                for (const post of result.data) {
                    const postCard = await createPostCard(post);
                    container.innerHTML += postCard;
                }

                attachCommentListeners();
            } else {
                container.innerHTML = `<p class="text-danger">Failed to load posts: ${result.message}</p>`;
            }
        } catch (error) {
            console.error(error);
            container.innerHTML = '<p class="text-danger">An error occurred while fetching posts.</p>';
        }
    }

    function attachCommentListeners() {
        const buttons = document.querySelectorAll('.submit-comment-btn');

        buttons.forEach(button => {
            button.addEventListener('click', async function () {
                const card = this.closest('.card');
                const postId = card.getAttribute('data-post-id');
                const input = card.querySelector('.comment-input');
                const successMsg = card.querySelector('.comment-success');
                const errorMsg = card.querySelector('.comment-error');

                const commentText = input.value.trim();
                if (!commentText) return;

                const payload = {
                    comment: commentText,
                    post_id: postId,
                    user_id: userId
                };

                console.log(payload);

                try {
                    const res = await fetch(commentUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await res.json();

                    if (res.ok && (data.status === 200 || data.status === 'success')) {
                        successMsg.style.display = 'inline';
                        errorMsg.style.display = 'none';
                        input.value = '';
                        loadPosts(); // Refresh comments
                    } else {
                        throw new Error(data.message || 'Unknown error');
                    }
                } catch (err) {
                    console.error(err);
                    errorMsg.style.display = 'inline';
                    successMsg.style.display = 'none';
                }
            });
        });
    }

    loadPosts();
    console.log('user_id:', userId);
</script>
</body>
</html>
