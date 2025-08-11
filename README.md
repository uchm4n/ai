## About This Project

This is the demonstration of the project that utilizes LLM models (Phi4 and DeepSeek) with predetermined parameters.
Response is being streamed to the client in real-time.

### Technology stack

Laravel, ReactJS, InertiaJS, TailwindCSS, PostgreSQL (pgVector), Docker, Selenium, Prism, Dusk

### TODO

- [x] Use vector database for embeddings
- [x] Use LLM models for embeddings. RAG
- [x] Scape data from the web
- [x] Output `specialized` data based on the scraped data + LLM embeddings
- [ ] Add MCP server
- [ ] Make tools work with multiple MCP servers

### Demo

<a href="https://nuc.ge/" target="_blank">nuc.ge</a>

<a href="https://nuc.ge/" target="_blank">
    <img alt="NUC" src="resources/img/1.gif" width="700"/>
</a>


### Extra features

#### StatusCode Module
- **Purpose**: Randomly picks HTTP status codes for testing purposes
- **Functionality**: Tests HTTP responses with various status codes
- **Use case**: Development and debugging of HTTP request handling

#### Post Module  
- **Purpose**: CRUD operations for Post entities
- **Functionality**: Returns JSON responses for placeholder data
- **External Integration**: Can be accessed via external API endpoints
- **Operations**: Create, Read, Update, Delete posts with JSON format
Try it:
```js
fetch('https://nuc.ge/posts')
      .then(response => response.json())
      .then(json => console.log(json))
```



