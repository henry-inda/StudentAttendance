// Basic client-side validation helpers
document.addEventListener('DOMContentLoaded', function(){
    // Attach validation to forms with data-validate="true"
    document.querySelectorAll('form[data-validate="true"]').forEach(function(form){
        form.addEventListener('submit', function(e){
            var valid = true;
            var firstInvalid = null;
            form.querySelectorAll('[required]').forEach(function(el){
                if(!el.value || el.value.trim() === ''){
                    valid = false;
                    el.classList.add('is-invalid');
                    if(!firstInvalid) firstInvalid = el;
                } else {
                    el.classList.remove('is-invalid');
                }
            });
            // Simple email validation
            form.querySelectorAll('input[type="email"]').forEach(function(el){
                var v = el.value;
                if (v && !/^\S+@\S+\.\S+$/.test(v)) {
                    valid = false; el.classList.add('is-invalid'); if(!firstInvalid) firstInvalid = el;
                }
            });

            if(!valid) {
                e.preventDefault();
                if(firstInvalid) firstInvalid.focus();
            }
        });
    });
});
