<script setup>
import { ref } from "vue";
import Icon from '@/Components/V2/Icon.vue';
import { toast } from '@/utils/useShop';

const email = ref('');
const sending = ref(false);

const subscribe = async () => {
    if (!email.value || sending.value) return;
    try{
        sending.value = true;
        const formData = new FormData();
        const _token  = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', _token);
        formData.append('email', email.value);
        const response = await fetch('/newsletter/send-email',{
            method: 'POST',
            body: formData,
        })

        if(!response.ok){
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        if(data.success){
            email.value = '';
            toast('Успешна претплата на билтенот!', true);
        }
        else{
            toast('Настана грешка при претплатата. Ве молиме обидете се повторно.');
        }
    }catch(error){
        console.error('Error subscribing to newsletter:', error);
        toast('Настана грешка при претплатата. Ве молиме обидете се повторно.');
    }finally{
        sending.value = false;
    }
};
</script>

<template>
    <div class="hf-news">
        <div>
            <h3>Не пропуштај ниту една одлична понуда!</h3>
            <p>Со внесување на вашата e-mail адреса се согласувате да ги добивате нашите маркетинг понуди.</p>
        </div>
        <form @submit.prevent="subscribe">
            <input v-model="email" type="email" placeholder="Вашата е-маил адреса" aria-label="Е-маил адреса" required />
            <button class="hf-btn hf-btn--dark" type="submit" :disabled="sending">
                Претплати се <Icon name="send" />
            </button>
        </form>
    </div>
</template>
