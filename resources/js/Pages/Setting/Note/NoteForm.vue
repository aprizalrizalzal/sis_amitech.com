<script setup>
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    note: '',
    description: '',
});

const props = defineProps({
    note: Object,
});

if (props.note) {
    form.note = props.note.note;
    form.description = props.note.description;
}

const submitForm = () => {
    if (!props.note) {
        form.post(route('store.note'), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset('note', 'description');
                emit('addNote');
            },
            onError: (errors) => {
                if (errors.note || errors.description) {
                    alert('addition failed!');
                } else {
                    const errorMessages = Object.values(errors).flat();
                    alert(`${errorMessages}`);
                }
            }
        });
    } else {
        const noteId = props.note.id;
        form.put(route('update.note', { id: noteId }), {
            preserveScroll: true,
            onSuccess: () => {
                form.data();
                emit('updateNote');
            },
            onError: (errors) => {
                if (errors.note || errors.description) {
                    alert('update failed!');
                } else {
                    const errorMessages = Object.values(errors).flat();
                    alert(`${errorMessages}`);
                }
            }
        });
    }
};

const emit = defineEmits([
    'addNote',
    'updateNote'
]);

</script>

<template>
    <div class="relative flex w-full flex-1 items-stretch">
        <div class="w-full">
            <form @submit.prevent="submitForm" class="space-y-4">
                <div>
                    <InputLabel for="note" value="Note" />
                    <TextInput id="note" type="text" v-model="form.note" class="mt-1 block w-full" placeholder="Note"
                        required autofocus />
                    <InputError :message="form.errors.note" />
                </div>
                <div>
                    <InputLabel for="description" value="Description note" />
                    <textarea id="description" type="text" v-model="form.description"
                        class="mt-1 block w-full border-green-700 focus:border-green-700 focus:ring-green-700 rounded shadow"
                        placeholder="Description" autofocus />
                    <InputError :message="form.errors.description" />
                </div>
                <div>
                    <PrimaryButton class="mt-6 mb-3" :disabled="form.processing">
                        {{ props.note ? 'Update' : 'Save' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
