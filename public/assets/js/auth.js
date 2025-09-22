/**
 * Auth Dynamic Effects
 * Parallax, animations, and interactive elements
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Mouse Parallax Effect
    function initParallax() {
        const parallaxImage = document.querySelector('.parallax-image');
        const leftPanel = document.querySelector('.left-panel');
        
        if (!parallaxImage || !leftPanel) return;
        
        let isMouseOver = false;
        
        leftPanel.addEventListener('mouseenter', function() {
            isMouseOver = true;
            this.classList.add('parallax-active');
        });
        
        leftPanel.addEventListener('mousemove', function(e) {
            if (!isMouseOver) return;
            
            const rect = this.getBoundingClientRect();
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const mouseX = e.clientX - rect.left;
            const mouseY = e.clientY - rect.top;
            
            // Calculate movement (subtle for elegance)
            const moveX = ((mouseX - centerX) / centerX) * 12;
            const moveY = ((mouseY - centerY) / centerY) * 8;
            const rotate = ((mouseX - centerX) / centerX) * 0.5; // Very subtle rotation
            
            // Apply smooth transform
            parallaxImage.style.transform = `scale(1.15) translate(${moveX}px, ${moveY}px) rotate(${rotate}deg)`;
        });
        
        leftPanel.addEventListener('mouseleave', function() {
            isMouseOver = false;
            this.classList.remove('parallax-active');
            
            // Return to original position smoothly
            parallaxImage.style.transform = 'scale(1.1) translate(0px, 0px) rotate(0deg)';
            
            // After transition, remove inline styles to let CSS animation resume
            setTimeout(() => {
                if (!isMouseOver) {
                    parallaxImage.style.transform = '';
                }
            }, 800);
        });
        
        // Add depth effect on click
        leftPanel.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const clickX = e.clientX - rect.left;
            const clickY = e.clientY - rect.top;
            
            // Create ripple effect
            const ripple = document.createElement('div');
            ripple.style.cssText = `
                position: absolute;
                left: ${clickX}px;
                top: ${clickY}px;
                width: 0;
                height: 0;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.1);
                transform: translate(-50%, -50%);
                animation: rippleEffect 0.8s ease-out;
                pointer-events: none;
                z-index: 10;
            `;
            
            // Add ripple keyframes if not already added
            if (!document.querySelector('#rippleKeyframes')) {
                const style = document.createElement('style');
                style.id = 'rippleKeyframes';
                style.textContent = `
                    @keyframes rippleEffect {
                        to {
                            width: 200px;
                            height: 200px;
                            opacity: 0;
                        }
                    }
                `;
                document.head.appendChild(style);
            }
            
            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 800);
        });
    }
    
    // Floating Particles
    function createFloatingParticles() {
        const leftPanel = document.querySelector('.left-panel');
        if (!leftPanel) return;
        
        const particlesContainer = document.createElement('div');
        particlesContainer.className = 'floating-particles';
        
        for (let i = 0; i < 5; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particlesContainer.appendChild(particle);
        }
        
        leftPanel.appendChild(particlesContainer);
    }
    
    // Enhanced Input Focus Effects
    function enhanceInputs() {
        const inputs = document.querySelectorAll('.custom-input');
        
        inputs.forEach(input => {
            input.classList.add('enhanced-input');
            
            // Add focus/blur animations
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
                this.parentElement.style.transition = 'transform 0.3s ease';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
            
            // Typing effect
            input.addEventListener('input', function() {
                if (this.value.length > 0) {
                    this.style.borderColor = 'rgba(255, 255, 255, 0.6)';
                } else {
                    this.style.borderColor = 'rgba(255, 255, 255, 0.2)';
                }
            });
        });
    }
    
    // Enhanced Button Effects
    function enhanceButton() {
        const submitButton = document.querySelector('.submit-button');
        if (!submitButton) return;
        
        submitButton.classList.add('enhanced-button');
        
        // Add loading state on form submit
        const form = submitButton.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                submitButton.classList.add('loading');
                submitButton.innerHTML = submitButton.innerHTML + '<span class="loading-spinner"></span>';
                submitButton.disabled = true;
                
                // Re-enable after 3 seconds if still on page (for errors)
                setTimeout(() => {
                    submitButton.classList.remove('loading');
                    submitButton.innerHTML = submitButton.innerHTML.replace('<span class="loading-spinner"></span>', '');
                    submitButton.disabled = false;
                }, 3000);
            });
        }
    }
    
    // Animated Text Effects
    function animateTexts() {
        const mainTitle = document.querySelector('.main-title');
        const subtitle = document.querySelector('.subtitle');
        const formTitle = document.querySelector('.form-title');
        
        if (mainTitle) mainTitle.classList.add('text-glow');
        if (subtitle) subtitle.classList.add('pulse-subtle');
        if (formTitle) formTitle.classList.add('text-glow');
    }
    
    // Progressive Enhancement Animation
    function progressiveEnhancement() {
        const rightPanel = document.querySelector('.right-panel');
        const leftPanel = document.querySelector('.left-panel');
        
        if (rightPanel) {
            rightPanel.classList.add('animated-gradient');
            rightPanel.classList.add('blur-animate');
        }
        
        // Stagger animations
        setTimeout(() => {
            const formContainer = document.querySelector('.form-container');
            if (formContainer) {
                formContainer.style.opacity = '0';
                formContainer.style.transform = 'translateY(20px)';
                formContainer.style.transition = 'all 0.6s ease';
                
                setTimeout(() => {
                    formContainer.style.opacity = '1';
                    formContainer.style.transform = 'translateY(0)';
                }, 200);
            }
        }, 100);
    }
    
    // Keyboard Interactions
    function addKeyboardEffects() {
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const activeElement = document.activeElement;
                if (activeElement && activeElement.tagName === 'INPUT') {
                    // Add a subtle pulse effect when Enter is pressed
                    activeElement.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        activeElement.style.transform = 'scale(1)';
                    }, 150);
                }
            }
        });
    }
    
    // Responsive Enhancements
    function handleResize() {
        const isMobile = window.innerWidth <= 768;
        
        if (isMobile) {
            // Disable heavy animations on mobile
            const particles = document.querySelector('.floating-particles');
            if (particles) particles.style.display = 'none';
        } else {
            const particles = document.querySelector('.floating-particles');
            if (particles) particles.style.display = 'block';
        }
    }
    
    // Auto-focus first input
    function autoFocus() {
        const firstInput = document.querySelector('input[type="text"], input[type="email"]');
        if (firstInput && window.innerWidth > 768) {
            setTimeout(() => firstInput.focus(), 500);
        }
    }
    
    // Error/Success Message Enhancements
    function enhanceAlerts() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            alert.style.transition = 'all 0.4s ease';
            
            setTimeout(() => {
                alert.style.opacity = '1';
                alert.style.transform = 'translateY(0)';
            }, 300);
            
            // Auto-hide success messages
            if (alert.classList.contains('alert-success')) {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => alert.remove(), 400);
                }, 4000);
            }
        });
    }
    
    // Theme Color Adaptation (for future use)
    function adaptiveColors() {
        const hour = new Date().getHours();
        const root = document.documentElement;
        
        if (hour >= 18 || hour <= 6) {
            // Night mode - deeper blues
            root.style.setProperty('--right-primary', '#001a4d');
            root.style.setProperty('--right-secondary', '#000d33');
        } else if (hour >= 6 && hour <= 12) {
            // Morning mode - brighter
            root.style.setProperty('--right-primary', '#0033ff');
            root.style.setProperty('--right-secondary', '#0066cc');
        }
    }
    
    // Initialize all effects
    initParallax();
    createFloatingParticles();
    enhanceInputs();
    enhanceButton();
    animateTexts();
    progressiveEnhancement();
    addKeyboardEffects();
    autoFocus();
    enhanceAlerts();
    adaptiveColors();
    
    // Handle window resize
    window.addEventListener('resize', handleResize);
    handleResize(); // Initial call
    
    // Console easter egg
    console.log('%cAthena CRM 🏔️', 'color: #0400ff; font-size: 20px; font-weight: bold;');
    console.log('%cPowered by Entropic Networks', 'color: #888; font-size: 12px;');
});

// Utility function for smooth scrolling (if needed in future)
function smoothScrollTo(target, duration = 800) {
    const targetElement = document.querySelector(target);
    if (!targetElement) return;
    
    const targetPosition = targetElement.offsetTop;
    const startPosition = window.pageYOffset;
    const distance = targetPosition - startPosition;
    let startTime = null;
    
    function animation(currentTime) {
        if (startTime === null) startTime = currentTime;
        const timeElapsed = currentTime - startTime;
        const run = ease(timeElapsed, startPosition, distance, duration);
        window.scrollTo(0, run);
        if (timeElapsed < duration) requestAnimationFrame(animation);
    }
    
    function ease(t, b, c, d) {
        t /= d / 2;
        if (t < 1) return c / 2 * t * t + b;
        t--;
        return -c / 2 * (t * (t - 2) - 1) + b;
    }
    
    requestAnimationFrame(animation);
}

// Export for use in other modules
window.AuthEffects = {
    smoothScrollTo
};