<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Login | Secure Access</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', system-ui, -apple-system, 'Segoe UI', Helvetica, Arial, sans-serif;
            background: linear-gradient(145deg, #eef2ff 0%, #e0e7ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        /* Main shell container */
        .auth-shell {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            animation: fadeSlideUp 0.5s ease-out;
        }

        /* Card design */
        .auth-card {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(0px);
            border-radius: 2rem;
            padding: 2rem 1.8rem 2.2rem 1.8rem;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.25), 0 8px 18px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .auth-card:hover {
            box-shadow: 0 30px 55px -15px rgba(0, 0, 0, 0.3);
        }

        /* Brand icon / logo */
        .brand-icon {
            width: 64px;
            height: 64px;
            border-radius: 22px;
            background: linear-gradient(135deg, #2f5cff, #17a38e);
            margin-bottom: 1.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 18px -6px rgba(47, 92, 255, 0.3);
            transition: all 0.2s;
        }

        .brand-icon svg {
            width: 34px;
            height: 34px;
            stroke: white;
            stroke-width: 1.7;
            fill: none;
        }

        /* Typography */
        h1 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1f2b4e, #2c3e66);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            letter-spacing: -0.3px;
            margin-bottom: 0.5rem;
        }

        .auth-card p {
            color: #5b6e8c;
            font-size: 0.95rem;
            margin-bottom: 1.8rem;
            border-left: 3px solid #2f5cff;
            padding-left: 0.8rem;
            font-weight: 450;
        }

        /* Form groups */
        .form-group {
            margin-bottom: 1.4rem;
        }

        label {
            display: block;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            color: #1f2a44;
            letter-spacing: -0.2px;
        }

        input {
            width: 100%;
            padding: 0.9rem 1rem;
            font-size: 1rem;
            font-family: inherit;
            border: 1.5px solid #e2e8f0;
            border-radius: 1.2rem;
            background: #ffffff;
            transition: all 0.2s ease;
            outline: none;
            color: #0f172a;
        }

        input:focus {
            border-color: #2f5cff;
            box-shadow: 0 0 0 4px rgba(47, 92, 255, 0.15);
        }

        input::placeholder {
            color: #b9c2d4;
            font-weight: 400;
            font-size: 0.9rem;
        }

        /* Button */
        .btn-primary {
            width: 100%;
            background: linear-gradient(105deg, #2f5cff, #1f4ad5);
            border: none;
            padding: 0.9rem 1rem;
            border-radius: 1.8rem;
            font-weight: 700;
            font-size: 1rem;
            font-family: inherit;
            color: white;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 0.6rem;
            margin-bottom: 1.2rem;
            box-shadow: 0 6px 14px rgba(47, 92, 255, 0.3);
            letter-spacing: 0.3px;
        }

        .btn-primary:hover {
            background: linear-gradient(105deg, #1f4ad5, #103bb0);
            transform: translateY(-2px);
            box-shadow: 0 12px 22px -8px rgba(47, 92, 255, 0.5);
        }

        .btn-primary:active {
            transform: translateY(1px);
            transition: 0.05s;
        }

        /* link text */
        .link-text {
            text-align: center;
            font-size: 0.9rem;
            color: #4a5a7a;
            border-top: 1px solid #edf2f7;
            padding-top: 1.4rem;
            margin-top: 0.2rem;
        }

        .link-text a {
            color: #2f5cff;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .link-text a:hover {
            color: #103bb0;
            text-decoration: underline;
        }

        /* Message boxes (error / success) */
        .message {
            padding: 0.85rem 1rem;
            border-radius: 1.2rem;
            margin-bottom: 1.4rem;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(4px);
        }

        .message.error {
            background: #fff1f0;
            border-left: 4px solid #e53e3e;
            color: #b91c1c;
        }

        .message.success {
            background: #e6fffa;
            border-left: 4px solid #17a38e;
            color: #0e6b5c;
        }

        /* Validation errors list (CI style) */
        .message.error ul, .message.error li {
            background: transparent;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .message.error li {
            margin-bottom: 2px;
        }

        /* small spacing adjustments */
        .form-group:last-of-type {
            margin-bottom: 0.2rem;
        }

        /* Responsive touches */
        @media (max-width: 520px) {
            .auth-card {
                padding: 1.6rem 1.3rem 1.8rem 1.3rem;
                border-radius: 1.6rem;
            }
            h1 {
                font-size: 1.8rem;
            }
            input {
                padding: 0.8rem 0.9rem;
            }
            .brand-icon {
                width: 54px;
                height: 54px;
            }
        }

        /* fade in animation */
        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Additional fine-tuning for validation messages that might appear as div.error */
        div.message.error {
            background-color: #fee2e2;
            color: #aa2e2e;
            border-left-color: #dc2626;
        }
    </style>
</head>
<body>
<div class="auth-shell">
    <div class="auth-card">
        <!-- Improved brand icon with SVG instead of empty div -->
        <div class="brand-icon">
            <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" stroke="white" stroke-linecap="round"/>
                <circle cx="12" cy="12" r="3" stroke="white" fill="rgba(255,255,255,0.2)"/>
            </svg>
        </div>
        <h1>Welcome back</h1>
        <p>Sign in with your mobile number or email and password.</p>

        <!-- Flashdata messages (error / success) -->
        <?php if ($this->session->flashdata('error')): ?>
            <div class="message error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="message success">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <!-- CodeIgniter validation errors block -->
        <?php echo validation_errors('<div class="message error"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>', '</div>'); ?>

        <form method="post" action="<?php echo site_url('login/authenticate'); ?>">
            <div class="form-group">
                <label for="mobile">📱 Mobile Number or Email</label>
                <input type="text" id="identity" name="identity" value="<?php echo set_value('identity'); ?>" placeholder="e.g., 9876543210 or hello@example.com" autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">🔒 Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" autocomplete="current-password">
            </div>

            <button class="btn-primary" type="submit">→ Sign in</button>
        </form>

        <div class="link-text" style="border-top:0;padding-top:0;margin-top:-0.2rem;margin-bottom:1rem;">
            <a href="<?php echo site_url('login/forgot-password'); ?>">Forgot Password?</a>
        </div>

        <div class="link-text">
            Don't have an account? <a href="<?php echo site_url('login/register'); ?>">Create account</a>
        </div>
    </div>
</div>
</body>
</html>
