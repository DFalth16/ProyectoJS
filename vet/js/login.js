const { createApp, reactive, ref } = Vue;

createApp({
  setup() {
    const form = reactive({ user: '', pass: '' });
    const error = ref('');
    const success = ref('');
    const loading = ref(false);

    async function submit() {
      error.value = '';
      success.value = '';

      if (!form.user.trim() || !form.pass) {
        error.value = 'Completa usuario y contraseña.';
        return;
      }

      loading.value = true;
      try {
        const res = await axios.post('inicio_sesion_api.php', {
          nombre_usuario: form.user.trim(),
          contrasena: form.pass
        }, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});

        const data = res.data;
        if (data && data.success) {
          success.value = data.message || 'Ingreso correcto. Redirigiendo...';
          setTimeout(() => {
            window.location.href = data.redirect || 'panel.php';
          }, 600);
        } else {
          error.value = data.message || 'Credenciales inválidas.';
        }
      } catch (err) {
        console.error(err);
        if (err.response && err.response.data && err.response.data.message) {
          error.value = err.response.data.message;
        } else {
          error.value = 'Error de conexión con el servidor.';
        }
      } finally {
        loading.value = false;
      }
    }

    return { form, error, success, loading, submit };
  }
}).mount('#app');
