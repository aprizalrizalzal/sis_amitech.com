<script setup>
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    name: '',
    description: '',
});

const props = defineProps({
    categorySparePart: Object,
});

if (props.categorySparePart) {
    form.name = props.categorySparePart.name;
    form.description = props.categorySparePart.description;
}

const submitForm = () => {
    if (!props.categorySparePart) {
        form.post(route('store.category.spare.part'), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset('name', 'description'),
                    emit('addCategorySparePart');
            },
            onError: (errors) => {
                if (errors.name) {
                    alert('addition failed!');
                } else {
                    const errorMessages = Object.values(errors).flat();
                    alert(`${errorMessages}`);
                }
            }
        });
    } else {
        const categorySparePartId = props.categorySparePart.id;
        form.put(route('update.category.spare.part', { id: categorySparePartId }), {
            preserveScroll: true,
            onSuccess: () => {
                form.data()
                emit('updateCategorySparePart');
            },
            onError: (errors) => {
                if (errors.name) {
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
    'addCategorySparePart',
    'updateCategorySparePart'
]);
</script>

<template>
    <div class="relative flex w-full flex-1 items-stretch">
        <div class="w-full">
            <form @submit.prevent="submitForm" class="mt-3 space-y-3">
                <div>
                    <InputLabel for="name" value="Name" />
                    <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" placeholder="Name"
                        required autofocus />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>
                <div>
                    <InputLabel for="description" value="Description" />
                    <textarea id="description" type="text"
                        class="mt-1 block w-full border-green-700 focus:border-green-700 focus:ring-green-700 rounded shadow"
                        v-model="form.description" placeholder="Description" required />
                    <InputError class="mt-2" :message="form.errors.description" />
                </div>
                <div>
                    <PrimaryButton class="mt-6 mb-3">
                        {{ props.categorySparePart ? 'Update' : 'Save' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>