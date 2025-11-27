class WebSocketClient {
    constructor() {
        this.socket = null;
        this.isConnected = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.reconnectDelay = 5000;
        this.pendingMessages = [];
        this.eventHandlers = {};
    }

    connect() {
        if (this.socket && this.isConnected) return;

        this.socket = new WebSocket('ws://localhost:8081');

        this.socket.onopen = () => {
            console.log('WebSocket connected');
            this.isConnected = true;
            this.reconnectAttempts = 0;
            
            // Authenticate connection with user info
            this.send('auth', {
                userId: window.currentUser.id,
                userRole: window.currentUser.role
            });

            // Send any pending messages
            while (this.pendingMessages.length > 0) {
                const msg = this.pendingMessages.shift();
                this.socket.send(JSON.stringify(msg));
            }
        };

        this.socket.onclose = () => {
            console.log('WebSocket connection closed');
            this.isConnected = false;
            this.handleReconnect();
        };

        this.socket.onerror = (error) => {
            console.error('WebSocket error:', error);
        };

        this.socket.onmessage = (event) => {
            const data = JSON.parse(event.data);
            this.handleMessage(data);
        };
    }

    handleReconnect() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            console.log(`Attempting to reconnect (${this.reconnectAttempts}/${this.maxReconnectAttempts})...`);
            setTimeout(() => this.connect(), this.reconnectDelay);
        }
    }

    send(type, data = {}) {
        const message = { type, ...data };
        
        if (!this.isConnected) {
            this.pendingMessages.push(message);
            return;
        }

        this.socket.send(JSON.stringify(message));
    }

    on(eventType, callback) {
        if (!this.eventHandlers[eventType]) {
            this.eventHandlers[eventType] = [];
        }
        this.eventHandlers[eventType].push(callback);
    }

    handleMessage(data) {
        if (!data.type) return;

        const handlers = this.eventHandlers[data.type] || [];
        handlers.forEach(handler => handler(data));

        // Handle specific message types
        switch (data.type) {
            case 'attendance_update':
                this.handleAttendanceUpdate(data);
                break;
            case 'schedule_update':
                this.handleScheduleUpdate(data);
                break;
            case 'excuse_response':
                this.handleExcuseResponse(data);
                break;
        }
    }

    handleAttendanceUpdate(data) {
        // Update attendance tables/charts if they exist
        const attendanceTable = document.querySelector('#attendance-table');
        if (attendanceTable) {
            // Refresh the attendance data
            location.reload();
        }

        // Show notification
        showToastNotification({
            title: 'Attendance Updated',
            message: `Your attendance has been marked for ${data.date}`,
            type: 'info'
        });
    }

    handleScheduleUpdate(data) {
        // Update schedule display if it exists
        const scheduleTable = document.querySelector('#schedule-table');
        if (scheduleTable) {
            // Refresh the schedule data
            location.reload();
        }

        // Show notification
        showToastNotification({
            title: 'Schedule Updated',
            message: 'A class schedule has been updated',
            type: 'warning'
        });
    }

    handleExcuseResponse(data) {
        // Update excuse request status if on the relevant page
        const excuseStatus = document.querySelector(`#excuse-${data.excuseId}-status`);
        if (excuseStatus) {
            excuseStatus.textContent = data.status;
            excuseStatus.className = `badge bg-${data.status === 'approved' ? 'success' : 'danger'}`;
        }

        // Show notification
        showToastNotification({
            title: 'Excuse Request Update',
            message: `Your excuse request has been ${data.status}`,
            type: data.status === 'approved' ? 'success' : 'danger'
        });
    }
}

// Initialize WebSocket client when the page loads
document.addEventListener('DOMContentLoaded', () => {
    // Get current user info from global variable (should be set in header)
    if (typeof window.currentUser === 'undefined') {
        console.error('Current user information not available');
        return;
    }

    // Initialize WebSocket client
    window.wsClient = new WebSocketClient();
    window.wsClient.connect();

    // Add online status indicator to navbar
    const navbarUserInfo = document.querySelector('#profileDropdown');
    if (navbarUserInfo) {
        const statusIndicator = document.createElement('span');
        statusIndicator.className = 'online-status-indicator ms-2';
        statusIndicator.id = 'online-status';
        navbarUserInfo.appendChild(statusIndicator);
    }

    // Update online status based on WebSocket connection
    function updateOnlineStatus(isOnline) {
        const indicator = document.querySelector('#online-status');
        if (indicator) {
            indicator.className = `online-status-indicator ms-2 ${isOnline ? 'online' : 'offline'}`;
            indicator.title = isOnline ? 'Online' : 'Offline';
        }
    }

    // Listen for online/offline events
    window.addEventListener('online', () => updateOnlineStatus(true));
    window.addEventListener('offline', () => updateOnlineStatus(false));
});