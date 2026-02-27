<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>API Tester - Tools</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <style>
      body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f4f4f9;
      }
      .container {
        max-width: 1000px;
        margin: auto;
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      }
      .method-select {
        width: 130px;
        font-weight: bold;
      }
      .method-get { color: #28a745; }
      .method-post { color: #007bff; }
      .method-put { color: #fd7e14; }
      .method-delete { color: #dc3545; }
      .method-patch { color: #6f42c1; }
      
      .form-group {
        margin-bottom: 20px;
      }
      label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
      }
      textarea {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 13px;
      }
      
      .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 30px;
        font-size: 16px;
        font-weight: 600;
      }
      .btn-primary:hover {
        background: linear-gradient(135deg, #5568d3 0%, #6838a2 100%);
      }
      .btn-primary:disabled {
        background: #ccc;
        cursor: not-allowed;
      }
      
      .result-container {
        margin-top: 25px;
        border-radius: 8px;
        overflow: hidden;
      }
      .result-header {
        background: #343a40;
        color: white;
        padding: 12px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }
      .status {
        font-weight: bold;
        font-size: 14px;
      }
      .status-ok { color: #a6e22e; }
      .status-error { color: #f92672; }
      
      .response-time {
        font-size: 12px;
        color: #adb5bd;
      }
      
      .result-body {
        background: #282c34;
        padding: 15px;
        min-height: 150px;
        max-height: 500px;
        overflow: auto;
      }
      .result-body pre {
        margin: 0;
        font-size: 13px;
      }
      
      .history-section {
        margin-top: 30px;
        border-top: 1px solid #eee;
        padding-top: 20px;
      }
      .history-item {
        padding: 10px 15px;
        margin-bottom: 8px;
        background: #f8f9fa;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.2s;
      }
      .history-item:hover {
        background: #e9ecef;
      }
      .history-method {
        font-weight: bold;
        font-size: 12px;
        padding: 3px 8px;
        border-radius: 4px;
        margin-right: 10px;
      }
      .history-url {
        flex: 1;
        font-size: 13px;
        color: #333;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      .history-status {
        font-size: 12px;
        margin-left: 10px;
      }
      
      .copy-btn {
        background: transparent;
        border: 1px solid #6c757d;
        color: #6c757d;
        padding: 4px 12px;
        font-size: 12px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
      }
      .copy-btn:hover {
        background: #6c757d;
        color: white;
      }
      
      .loading {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s linear infinite;
        margin-right: 8px;
      }
      @keyframes spin {
        to { transform: rotate(360deg); }
      }
      
      .nav-tabs {
        margin-bottom: 20px;
      }
      .tab-content {
        border: 1px solid #dee2e6;
        border-top: none;
        padding: 20px;
        border-radius: 0 0 8px 8px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h2 class="mb-4">🛠️ API Tester</h2>

      <div class="form-group">
        <label>Method & URL</label>
        <div class="d-flex gap-2">
          <select id="method" class="form-select method-select">
            <option value="GET" class="method-get">GET</option>
            <option value="POST" class="method-post">POST</option>
            <option value="PUT" class="method-put">PUT</option>
            <option value="PATCH" class="method-patch">PATCH</option>
            <option value="DELETE" class="method-delete">DELETE</option>
          </select>
          <input
            type="text"
            id="url"
            class="form-control"
            placeholder="https://api.example.com/data"
            value="https://jsonplaceholder.typicode.com/todos/1"
          />
        </div>
      </div>

      <ul class="nav nav-tabs" id="requestTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="headers-tab" data-bs-toggle="tab" data-bs-target="#headers-pane" type="button" role="tab">Headers</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="body-tab" data-bs-toggle="tab" data-bs-target="#body-pane" type="button" role="tab">Body</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="auth-tab" data-bs-toggle="tab" data-bs-target="#auth-pane" type="button" role="tab">Auth</button>
        </li>
      </ul>
      
      <div class="tab-content" id="requestTabContent">
        <div class="tab-pane fade show active" id="headers-pane" role="tabpanel">
          <div class="form-group mb-0">
            <label>Headers (JSON format)</label>
            <textarea
              id="headers"
              class="form-control"
              placeholder='{"Content-Type": "application/json", "Authorization": "Bearer token"}'
              rows="4"
            >
{
  "Content-Type": "application/json"
}</textarea>
          </div>
        </div>
        
        <div class="tab-pane fade" id="body-pane" role="tabpanel">
          <div class="form-group mb-0">
            <label>Request Body (JSON format - for POST/PUT/PATCH)</label>
            <textarea
              id="body"
              class="form-control"
              placeholder='{"name": "John Doe", "email": "john@example.com"}'
              rows="6"
            ></textarea>
          </div>
        </div>
        
        <div class="tab-pane fade" id="auth-pane" role="tabpanel">
          <div class="form-group mb-0">
            <label>Authorization Header (auto-generated)</label>
            <select id="authType" class="form-select mb-2">
              <option value="none">No Auth</option>
              <option value="bearer">Bearer Token</option>
              <option value="basic">Basic Auth</option>
              <option value="apiKey">API Key</option>
            </select>
            <input
              type="text"
              id="authValue"
              class="form-control"
              placeholder="Enter token or credentials"
              disabled
            />
          </div>
        </div>
      </div>

      <div class="mt-3">
        <button onclick="sendRequest()" id="sendBtn" class="btn btn-primary">
          🚀 Send Request
        </button>
        <button onclick="clearForm()" class="btn btn-outline-secondary ms-2">
          🗑️ Clear
        </button>
      </div>

      <div class="result-container">
        <div class="result-header">
          <div>
            <span id="status" class="status">Status: -</span>
            <span id="responseTime" class="response-time ms-2"></span>
          </div>
          <button class="copy-btn" onclick="copyResponse()">📋 Copy</button>
        </div>
        <div class="result-body">
          <pre><code id="response" class="language-json">Response will appear here...</code></pre>
        </div>
      </div>

      <div class="history-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">📜 Request History</h5>
          <button class="btn btn-sm btn-outline-secondary" onclick="clearHistory()">Clear History</button>
        </div>
        <div id="historyList">
          <p class="text-muted">No requests yet. Your request history will appear here.</p>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>
      let requestHistory = [];
      let currentResponse = '';
      
      // Auth type change handler
      document.getElementById('authType').addEventListener('change', function() {
        const authValue = document.getElementById('authValue');
        if (this.value === 'none') {
          authValue.disabled = true;
          authValue.placeholder = 'No auth selected';
          authValue.value = '';
        } else {
          authValue.disabled = false;
          if (this.value === 'bearer') {
            authValue.placeholder = 'Enter bearer token';
          } else if (this.value === 'basic') {
            authValue.placeholder = 'username:password';
          } else if (this.value === 'apiKey') {
            authValue.placeholder = 'Enter API key';
          }
        }
      });
      
      // Update method color
      document.getElementById('method').addEventListener('change', function() {
        this.className = 'form-select method-select method-' + this.value.toLowerCase();
      });
      
      // Set initial color
      document.getElementById('method').className = 'form-select method-select method-get';
      
      async function sendRequest() {
        const url = document.getElementById('url').value.trim();
        const method = document.getElementById('method').value;
        const headersRaw = document.getElementById('headers').value;
        const bodyRaw = document.getElementById('body').value;
        const authType = document.getElementById('authType').value;
        const authValue = document.getElementById('authValue').value;
        
        const responseElement = document.getElementById('response');
        const statusElement = document.getElementById('status');
        const responseTimeElement = document.getElementById('responseTime');
        const sendBtn = document.getElementById('sendBtn');
        
        if (!url) {
          alert('Please enter a URL');
          return;
        }
        
        responseElement.textContent = 'Loading...';
        statusElement.textContent = 'Status: Sending...';
        statusElement.className = 'status';
        responseTimeElement.textContent = '';
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<span class="loading"></span> Sending...';
        
        const startTime = performance.now();
        
        try {
          let headers = {};
          try {
            headers = JSON.parse(headersRaw || '{}');
          } catch (e) {
            throw new Error('Invalid JSON in headers: ' + e.message);
          }
          
          // Add auth header
          if (authType === 'bearer' && authValue) {
            headers['Authorization'] = 'Bearer ' + authValue;
          } else if (authType === 'basic' && authValue) {
            headers['Authorization'] = 'Basic ' + btoa(authValue);
          } else if (authType === 'apiKey' && authValue) {
            headers['X-API-Key'] = authValue;
          }
          
          const options = {
            method: method,
            headers: headers,
          };
          
          if (method !== 'GET' && method !== 'DELETE' && bodyRaw) {
            options.body = bodyRaw;
          }
          
          const res = await fetch(url, options);
          const endTime = performance.now();
          const duration = Math.round(endTime - startTime);
          
          let data;
          const contentType = res.headers.get('content-type');
          if (contentType && contentType.includes('application/json')) {
            data = await res.json();
            currentResponse = JSON.stringify(data, null, 2);
          } else {
            data = await res.text();
            currentResponse = data;
          }
          
          statusElement.textContent = `Status: ${res.status} ${res.statusText}`;
          statusElement.className = 'status ' + (res.ok ? 'status-ok' : 'status-error');
          responseTimeElement.textContent = `⏱️ ${duration}ms`;
          
          responseElement.textContent = currentResponse;
          hljs.highlightElement(responseElement);
          
          // Add to history
          addToHistory(method, url, res.status, duration);
          
        } catch (error) {
          statusElement.textContent = 'Status: Error';
          statusElement.className = 'status status-error';
          responseTimeElement.textContent = '';
          currentResponse = 'Failed to fetch data. Check console or CORS issues.\n\nDetails: ' + error.message;
          responseElement.textContent = currentResponse;
          
          addToHistory(method, url, 'Error', 0);
        } finally {
          sendBtn.disabled = false;
          sendBtn.innerHTML = '🚀 Send Request';
        }
      }
      
      function addToHistory(method, url, status, duration) {
        const item = {
          method: method,
          url: url,
          status: status,
          duration: duration,
          timestamp: new Date().toLocaleTimeString()
        };
        
        requestHistory.unshift(item);
        if (requestHistory.length > 20) {
          requestHistory.pop();
        }
        
        renderHistory();
      }
      
      function renderHistory() {
        const historyList = document.getElementById('historyList');
        
        if (requestHistory.length === 0) {
          historyList.innerHTML = '<p class="text-muted">No requests yet. Your request history will appear here.</p>';
          return;
        }
        
        let html = '';
        requestHistory.forEach((item, index) => {
          const statusClass = item.status === 'Error' ? 'status-error' : (item.status >= 200 && item.status < 300 ? 'status-ok' : 'status-error');
          html += `
            <div class="history-item" onclick="loadFromHistory(${index})">
              <div class="d-flex align-items-center">
                <span class="history-method method-${item.method.toLowerCase()}">${item.method}</span>
                <span class="history-url">${item.url}</span>
              </div>
              <div>
                <span class="history-status ${statusClass}">${item.status}</span>
                ${item.duration > 0 ? `<span class="history-status text-muted">${item.duration}ms</span>` : ''}
              </div>
            </div>
          `;
        });
        
        historyList.innerHTML = html;
      }
      
      function loadFromHistory(index) {
        const item = requestHistory[index];
        document.getElementById('method').value = item.method;
        document.getElementById('url').value = item.url;
        document.getElementById('method').className = 'form-select method-select method-' + item.method.toLowerCase();
      }
      
      function clearHistory() {
        requestHistory = [];
        renderHistory();
      }
      
      function clearForm() {
        document.getElementById('url').value = '';
        document.getElementById('headers').value = '{\n  "Content-Type": "application/json"\n}';
        document.getElementById('body').value = '';
        document.getElementById('authType').value = 'none';
        document.getElementById('authValue').disabled = true;
        document.getElementById('authValue').value = '';
        document.getElementById('response').textContent = 'Response will appear here...';
        document.getElementById('status').textContent = 'Status: -';
        document.getElementById('status').className = 'status';
        document.getElementById('responseTime').textContent = '';
        currentResponse = '';
      }
      
      function copyResponse() {
        if (currentResponse) {
          navigator.clipboard.writeText(currentResponse).then(() => {
            const copyBtn = document.querySelector('.copy-btn');
            copyBtn.textContent = '✅ Copied!';
            setTimeout(() => {
              copyBtn.textContent = '📋 Copy';
            }, 2000);
          });
        }
      }
    </script>
  </body>
</html>
