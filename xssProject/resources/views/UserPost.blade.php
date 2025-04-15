<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Post</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h2>Create a New Post</h2>

   
    <a href="http://127.0.0.1:8000/api/view_posts">
        <button type="button" style="margin-top: 10px; padding: 8px 16px; font-size: 14px;">← Back to View Posts</button>
    </a>


    <form id="postForm">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br><br>

        <label for="content">Content:</label><br>
        <textarea id="content" name="content" rows="5" cols="40" required></textarea><br><br>

        <button type="submit">Submit Post</button>
    </form>

    <p id="responseMessage" style="color: green;"></p>

    <script>
        document.getElementById('postForm').addEventListener('submit', async function (event) {
            event.preventDefault();

            const title = document.getElementById('title').value.trim();
            const content = document.getElementById('content').value.trim();
            const userId = localStorage.getItem('user_id');
            //const token = localStorage.getItem('access_token');

            if (!userId) {
                alert("Missing user_id or access_token. Please log in again.");
                return;
            }

            const payload = {
                title: title,
                content: content,
                user_id: userId
            };

            try {
                const response = await fetch('http://127.0.0.1:8000/api/post/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                       // 'Authorization': 'Bearer ' + token
                    },
                    body: JSON.stringify(payload)
                });
                console.log('Response:', response);
                const data = await response.json();

                if (response.ok && (data.status === 200 || data.status === 'success')) {
                    document.getElementById('responseMessage').textContent = '✅ Post created successfully!';
                    document.getElementById('postForm').reset();
                } else {
                    document.getElementById('responseMessage').textContent = '❌ Failed to create post: ' + (data.message || 'Unknown error');
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('responseMessage').textContent = '❌ Error while creating post.';
            }
        });
    </script>
</body>
</html>
