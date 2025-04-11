const express = require('express');
const app = express();
const cors = require('cors');
app.use(cors());
app.use(express.json());

let posts = [];

app.get('/posts', (req, res) => {
  res.json(posts);
});

app.post('/posts', (req, res) => {
  const { message, name } = req.body;
  if (message) {
    posts.push({ message, name, time: new Date() });
    res.json({ success: true });
  } else {
    res.status(400).json({ error: 'No message sent' });
  }
});

app.listen(3000, () => console.log('Server running on http://localhost:3000'));