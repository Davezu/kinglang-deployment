# KingLang Chat System Integration Guide

## Overview
This guide explains how to integrate the chat system into your existing KingLang booking application.

## Files Created

### Database Migration
- `database/migrations/create_chat_tables.sql` - Creates the necessary database tables

### Backend (PHP)
- `app/models/ChatModel.php` - Main chat model with all database operations
- `app/controllers/client/ChatController.php` - Client-side chat API controller
- `app/controllers/admin/AdminChatController.php` - Admin chat management controller

### Frontend
- `public/js/chat-widget-core.js` - Main chat widget JavaScript
- `public/css/chat-widget.css` - Chat widget styles
- `public/js/admin-chat.js` - Admin chat management interface
- `app/views/admin/chat/index.php` - Admin chat management page

### Routes
- API routes added to `routes/web.php` for both client and admin chat functionality

## Installation Steps

### 1. Database Setup
Run the migration to create chat tables:
```sql
-- Execute the contents of database/migrations/create_chat_tables.sql
-- This will create: conversations, messages, bot_responses tables
```

### 2. Add Chat Widget to Client Pages
Add these lines to any client page where you want the chat widget:

```php
<!-- In the <head> section -->
<link rel="stylesheet" href="/public/css/chat-widget.css">

<!-- Before closing </body> tag -->
<script>
// Set user login status for chat widget
var userLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
</script>
<script src="/public/js/chat-widget-core.js"></script>
```

### 3. Add Admin Navigation
Add to admin sidebar navigation:
```php
<li class="nav-item">
    <a class="nav-link" href="/admin/chat">
        <i class="fas fa-comments"></i>
        <span>Chat Management</span>
    </a>
</li>
```

## Usage

### For Clients
1. The chat widget appears as a floating button on the bottom-right
2. Clicking opens a chat panel with quick question buttons
3. Bot responds automatically to common queries
4. If bot can't help, offers to connect with human agent
5. Real-time messaging with admin agents

### For Admins
1. Access via `/admin/chat`
2. View pending, active, and ended conversations
3. Take over conversations from bot
4. Chat in real-time with clients
5. Manage bot responses and keywords
6. View conversation statistics

## API Endpoints

### Client API
- `POST /api/chat/conversation` - Get or create conversation
- `GET /api/chat/messages/{id}` - Get conversation messages
- `POST /api/chat/send` - Send message
- `POST /api/chat/request-human` - Request human assistance
- `POST /api/chat/end` - End conversation

### Admin API
- `GET /api/admin/chat/dashboard` - Get dashboard data
- `GET /api/admin/chat/pending` - Get pending conversations
- `GET /api/admin/chat/active` - Get active conversations
- `POST /api/admin/chat/assign` - Assign conversation to admin
- `POST /api/admin/chat/send` - Send admin message
- `GET /api/admin/chat/bot-responses` - Manage bot responses

## Features

### Bot Responses
- Keyword-based automatic responses
- Covers common booking queries (pricing, booking process, cancellation, etc.)
- Fallback to human assistance when bot can't help
- Admin can add/edit/delete bot responses

### Real-time Features
- Polling-based message updates (every 3-5 seconds)
- Unread message indicators
- Conversation status updates
- Admin notifications for new conversations

### Security
- Session-based authentication
- User ownership validation for conversations
- Admin role checking for management features
- SQL injection protection with prepared statements

## Customization

### Styling
Modify `public/css/chat-widget.css` to match your brand colors and design.

### Bot Responses
Add more responses through the admin interface or directly in the database.

### Polling Interval
Adjust polling frequency in the JavaScript files (default: 3-5 seconds).

## Troubleshooting

### Chat Widget Not Appearing
1. Check if user is logged in (userLoggedIn variable)
2. Verify CSS and JS files are loading
3. Check browser console for JavaScript errors

### Messages Not Sending
1. Verify database connection
2. Check API endpoints are accessible
3. Ensure proper session management

### Admin Interface Issues
1. Verify admin authentication
2. Check database tables exist
3. Ensure proper file permissions

## Future Enhancements

Potential improvements you could add:
1. WebSocket integration for true real-time messaging
2. File upload support in chat
3. Chat transcripts via email
4. Advanced bot AI integration
5. Mobile app push notifications
6. Chat analytics and reporting
7. Automated chat routing based on query type

## Support

For technical support or questions about the chat system integration, refer to the code comments and this documentation.
