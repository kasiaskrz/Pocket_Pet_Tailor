<div id="modalOverlay"></div>

<div id="loginModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="loginFormContainer">
            <h2>Login</h2>
            <form id="loginForm" onsubmit="loginUser(event)">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <div class="remember-me-container">
                    <label for="rememberMe" class="remember-me-label">Remember Me</label>
                    <input type="checkbox" id="rememberMe">
                </div>

                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="#" id="showRegister">Register</a></p>
        </div>
        <div id="registerFormContainer" style="display: none;">
            <h2>Register</h2>
            <form id="registerForm" onsubmit="registerUser(event)">
                <label for="register_username">Username</label>
                <input type="text" id="register_username" name="username" required>
                <label for="register_email">Email</label>
                <input type="email" id="register_email" name="email" required>
                <label for="register_password">Password</label>
                <input type="password" id="register_password" name="password" required>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="#" id="showLogin">Login</a></p>
        </div>
    </div>
</div>