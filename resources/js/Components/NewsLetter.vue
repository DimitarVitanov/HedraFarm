<script setup>
import { ref } from "vue";

const email = ref('');

const fireMessage = (message, success = false, reload = false) => {
  Swal.fire({
    position: "top",
    toast: true,
    icon: success ? "success" : "error",
    title: message,
    showConfirmButton: false,
    timer: 1500,
  }).then(() => {
    if (reload) {
      location.reload();
    }
  });
};
const subscribe = async () => {
    try{
        const formData = new FormData();
        const _token  = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', _token);
        formData.append('email', email.value);
        const response = await fetch('newsletter/send-email',{
            method: 'POST',
            body: formData,
        })

        if(!response.ok){
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        if(data.success){
            fireMessage('Успешна претплата на билтенот!', true, true);
        }
        else{
            fireMessage('Настана грешка при претплатата. Ве молиме обидете се повторно.');
        }
    }catch(error){
        console.error('Error subscribing to newsletter:', error);
    }
};
</script>
<template>

       <!-- newsletter area -->
       <div class="newsletter-area pb-100">
        <div class="container wow fadeInUp" data-wow-delay=".25s">
            <div class="newsletter-wrap">
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="newsletter-content">
                            <h3>Не пропуштај ниту една одлична понуда!</h3>
                            <p>Со внесување на вашата e-mail адреса се согласувате да ги добивате нашите маркетинг понуди.
                            </p>
                            <div class="subscribe-form">
                                <form>
                                    <input type="email" v-model="email" class="form-control" placeholder="Вашата е-маил адреса">
                                    <button @click="subscribe()" class="theme-btn" type="submit">
                                        Претплати се <i class="far fa-paper-plane"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- newsletter area end -->
</template
