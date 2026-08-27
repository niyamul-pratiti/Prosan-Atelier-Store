(function(){
  function getCsrfToken(){
    var meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
  }

  function normalizeQty(form){
    var qty = form.querySelector('input[name="quantity"]');
    if(qty && parseInt(qty.value || '1', 10) < 1){ qty.value = 1; }
  }

  function showToast(message, type){
    var toast = document.querySelector('.prosan-ajax-toast');
    if(!toast){
      toast = document.createElement('div');
      toast.className = 'prosan-ajax-toast';
      document.body.appendChild(toast);
    }
    toast.textContent = message || 'Cart updated.';
    toast.classList.remove('is-error','is-visible');
    if(type === 'error') toast.classList.add('is-error');
    window.setTimeout(function(){ toast.classList.add('is-visible'); }, 20);
    window.clearTimeout(window.prosanToastTimer);
    window.prosanToastTimer = window.setTimeout(function(){ toast.classList.remove('is-visible'); }, 2200);
  }

  function updateCartUi(data){
    if(!data) return;
    document.querySelectorAll('[data-cart-count]').forEach(function(el){
      el.textContent = data.cart_count || 0;
      if(Number(data.cart_count || 0) > 0){ el.classList.add('has-items'); }
      else { el.classList.remove('has-items'); }
    });
    document.querySelectorAll('[data-cart-total]').forEach(function(el){
      el.textContent = data.cart_total_formatted || '৳0';
    });
    var panel = document.getElementById('prosan-cart-panel-body');
    if(panel && data.cart_html){
      panel.innerHTML = data.cart_html;
    }
  }

  document.addEventListener('submit', function(e){
    var form = e.target;
    if(form.matches('.product-item form, .product-form')){
      normalizeQty(form);
    }

    if(!form.matches('.js-ajax-add-to-cart')) return;
    e.preventDefault();

    var submit = form.querySelector('[type="submit"]');
    var original = submit ? submit.innerHTML : '';
    if(submit){
      submit.disabled = true;
      submit.classList.add('is-loading');
      submit.innerHTML = '<span>Adding...</span>';
    }

    fetch(form.action, {
      method: 'POST',
      body: new FormData(form),
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken()
      },
      credentials: 'same-origin'
    })
    .then(function(response){
      return response.text().then(function(body){
        var data;

        try {
          data = body ? JSON.parse(body) : {};
        } catch (parseError) {
          throw { message: 'Could not add product to cart. Please refresh the page and try again.' };
        }

        if(!response.ok) throw data;
        return data;
      });
    })
    .then(function(data){
      updateCartUi(data);
      showToast(data.message || 'Product added to cart.', 'success');

      var cartCanvas = document.getElementById('offcanvasCart');
      if(cartCanvas && window.bootstrap && window.bootstrap.Offcanvas){
        window.bootstrap.Offcanvas.getOrCreateInstance(cartCanvas).show();
      }
    })
    .catch(function(error){
      var message = error && error.message ? error.message : 'Could not add product to cart.';
      if(error && error.errors){
        var firstKey = Object.keys(error.errors)[0];
        if(firstKey && error.errors[firstKey] && error.errors[firstKey][0]) message = error.errors[firstKey][0];
      }
      showToast(message, 'error');
    })
    .finally(function(){
      if(submit){
        submit.disabled = false;
        submit.classList.remove('is-loading');
        submit.innerHTML = original;
      }
    });
  });
})();
