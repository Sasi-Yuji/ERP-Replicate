<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | EXCEL COLLEGE OF ENGINEERING & TECHNOLOGY</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body, html { height: 100%; overflow: hidden; }
        
        .bg-system {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('<?= base_url('assets/images/campus_login.png') ?>');
            background-size: cover;
            background-position: center;
            z-index: -1;
        }
        
        .bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.3));
            backdrop-filter: blur(5px);
            z-index: 0;
        }

        .login-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 80px;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 5%;
            z-index: 1000;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        
        .brand { display: flex; align-items: center; gap: 15px; }
        .nav-links { display: flex; gap: 30px; font-size: 0.9rem; font-weight: 700; color: #013220; }

        .main-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 10%;
            padding-top: 80px;
            position: relative;
            z-index: 1;
        }
        
        .left-content { max-width: 650px; color: white; }
        .left-content h1 { font-size: 4rem; font-weight: 900; line-height: 1.1; margin-bottom: 25px; }
        .left-content span.highlight { color: #50C878; }
        .left-content p { font-size: 1.2rem; opacity: 0.95; margin-bottom: 35px; line-height: 1.6; }
        
        .right-content {
            width: 440px;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.4);
            animation: slideInRight 0.8s ease-out;
        }
        
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .card-header { display: flex; justify-content: flex-end; margin-bottom: 30px; gap: 20px; align-items: center; }
        .login-pill { background: #013220; color: white; padding: 10px 28px; border-radius: 50px; font-weight: 800; font-size: 0.9rem; }
        
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 0.75rem; font-weight: 800; color: #013220; margin-bottom: 6px; text-transform: uppercase; }
        
        .form-group input {
            width: 100%;
            padding: 13px 18px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            font-size: 0.95rem;
            outline: none;
        }
        
        .form-group input:focus { border-color: #50C878; background: #fff; }
        
        .captcha-row { display: flex; gap: 15px; margin-bottom: 25px; }
        .captcha-box {
            background: #f1f5f9;
            padding: 0 25px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 1.2rem;
            color: #013220;
            border: 1.5px solid #e2e8f0;
        }
        
        .sign-in-btn {
            width: 100%;
            background: #013220;
            color: white;
            border: none;
            padding: 16px;
            border-radius: 60px;
            font-weight: 900;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            cursor: pointer;
        }

        .alert-error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 0.8rem;
            margin-bottom: 20px;
            font-weight: 600;
            border: 1px solid #fecaca;
        }
    </style>
</head>
<body>

    <div class="bg-system"></div>
    <div class="bg-overlay"></div>

    <header class="login-header">
        <div class="brand">
            <svg viewBox="0 0 24 24" fill="none" stroke="#013220" stroke-width="2" width="32" height="32">
                <path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            </svg>
            <div style="line-height: 1.2;">
                <div style="font-weight: 900; font-size: 1.1rem; color: #013220; letter-spacing: 0.5px;">EXCEL COLLEGE</div>
                <div style="font-size: 0.7rem; color: #50C878; font-weight: 800; text-transform: uppercase;">Engineering Excellence</div>
            </div>
        </div>
        <nav class="nav-links">
            <span>About us</span><span>Admissions</span><span>Academics</span><span>Placements</span><span>Campus Life</span>
        </nav>
    </header>

    <div class="main-container">
        <div class="left-content">
            <h1>Explore the World <br>of Our <span class="highlight">Graduates</span></h1>
            <p>Empowering the next generation of engineers with innovation, discipline, and state-of-the-art academic learning.</p>
        </div>

        <div class="right-content">
            <div class="card-header">
                <span style="font-weight:700; color:#64748b; font-size:0.9rem;">New Applicant</span>
                <span class="login-pill">Login</span>
            </div>
            
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert-error">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('login/process') ?>" method="POST">
                <div class="form-group">
                    <label>Mobile Number/Email ID</label>
                    <input type="text" name="username" placeholder="admin" required>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="admin123" required>
                </div>
                
                <div class="form-group">
                    <label>Captcha Verification</label>
                    <div class="captcha-row">
                        <div style="flex:1;">
                            <input type="text" name="captcha" placeholder="Result" required>
                        </div>
                        <div class="captcha-box">
                            <?= $num1 ?> + <?= $num2 ?>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="sign-in-btn">
                    Sign In
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" width="22" height="22"><path d="M5 12h14m-7-7l7 7-7 7" /></svg>
                </button>
            </form>
        </div>
    </div>

</body>
</html>
