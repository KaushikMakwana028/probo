<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Verify Reset Code</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(145deg, #eef2ff 0%, #e0e7ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .auth-shell { width: 100%; max-width: 480px; margin: 0 auto; }
        .auth-card {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 2rem;
            padding: 2rem 1.8rem 2.2rem 1.8rem;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.25), 0 8px 18px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        h1 { font-size: 2rem; font-weight: 700; color: #1f2b4e; margin-bottom: 0.5rem; }
        .auth-card p { color: #5b6e8c; font-size: 0.95rem; margin-bottom: 1.8rem; border-left: 3px solid #2f5cff; padding-left: 0.8rem; }
        .form-group { margin-bottom: 1.4rem; }
        label { display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.5rem; color: #1f2a44; }
        input {
            width: 100%; padding: 0.9rem 1rem; font-size: 1rem; font-family: inherit;
            border: 1.5px solid #e2e8f0; border-radius: 1.2rem; background: #ffffff; outline: none;
        }
        input:focus { border-color: #2f5cff; box-shadow: 0 0 0 4px rgba(47, 92, 255, 0.15); }
        .btn-primary {
            width: 100%; background: linear-gradient(105deg, #2f5cff, #1f4ad5); border: none; padding: 0.9rem 1rem;
            border-radius: 1.8rem; font-weight: 700; font-size: 1rem; color: white; cursor: pointer; margin-top: 0.6rem;
        }
        .message { padding: 0.85rem 1rem; border-radius: 1.2rem; margin-bottom: 1.4rem; font-size: 0.85rem; }
        .message.error { background: #fff1f0; border-left: 4px solid #e53e3e; color: #b91c1c; }
        .message.success { background: #e6fffa; border-left: 4px solid #17a38e; color: #0e6b5c; }
    </style>
</head>
<body>
<div class="auth-shell">
    <div class="auth-card">
        <h1>Verify Code</h1>
        <p>Enter the reset code sent to your email.</p>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="message error"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="message success"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>

        <?php echo validation_errors('<div class="message error">', '</div>'); ?>

        <form method="post" action="<?php echo site_url('login/verify-reset-code'); ?>">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo set_value('email', isset($reset_email) ? $reset_email : ''); ?>" placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label for="reset_code">Reset Code</label>
                <input type="text" id="reset_code" name="reset_code" value="<?php echo set_value('reset_code'); ?>" placeholder="Enter 6-digit code">
            </div>

            <button class="btn-primary" type="submit">Verify Code</button>
        </form>
    </div>
</div>
</body>
</html>
