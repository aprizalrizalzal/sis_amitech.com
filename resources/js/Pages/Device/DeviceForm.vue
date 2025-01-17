<script setup>
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DropdownSelect from '@/Components/DropdownSelect.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    device_type_id: '',
    model: '',
    serial_number: '',
});

const props = defineProps({
    deviceTypes: Array,

    device: Object,
    deviceType: Object,
});

if (props.device) {
    form.device_type_id = props.device.device_type_id;
    form.model = props.device.model;
    form.serial_number = props.device.serial_number;
}

const submitForm = () => {
    if (!props.device) {
        form.post(route('store.device'), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset('model', 'serial_number'),
                emit('addDevice');
            },
            onError: (errors) => {
                if (errors.device_type_id || errors.model || errors.serial_number) {
                    alert('addition failed!');
                } else {
                    const errorMessages = Object.values(errors).flat();
                    alert(`${errorMessages}`);
                }
            }
        });
    } else {
        const deviceId = props.device.id;
        form.put(route('update.device', { id: deviceId }), {
            preserveScroll: true,
            onSuccess: () => {
                form.data(),
                emit('updateDevice');
            },
            onError: (errors) => {
                if (errors.device_type_id || errors.model || errors.serial_number) {
                    alert('update failed!');
                } else {
                    const errorMessages = Object.values(errors).flat();
                    alert(`${errorMessages}`);
                }
            }
        });
    }

};

const emit = defineEmits(
    [
        'addDevice', 
        'updateDevice'
    ]
);
</script>

<template>
    <div class="relative flex w-full flex-1 items-stretch">
        <div class="w-full">
            <form @submit.prevent="submitForm" class="mt-3 space-y-3">
                <div>
                    <DropdownSelect id="device_type_id" label="Device Type" optionProperty="type_name"
                        valueProperty="id" :options="deviceTypes" v-model="form.device_type_id"
                        :placeholder='props.device ? props.device.device_type.type_name : "Select Device Type"'
                        class="w-full" />
                    <InputError class="mt-2" :message="form.errors.device_type_id" />
                </div>
                <div>
                    <InputLabel for="model" value="Model" />
                    <TextInput id="model" type="text" class="mt-1 block w-full" v-model="form.model" placeholder="Model"
                        required autofocus />
                    <InputError class="mt-2" :message="form.errors.model" />
                </div>
                <div>
                    <InputLabel for="serial_number" value="Serial Number" />
                    <TextInput id="serial_number" type="text" class="mt-1 block w-full" v-model="form.serial_number"
                        placeholder="Serial Number" required />
                    <InputError class="mt-2" :message="form.errors.serial_number" />
                </div>
                <div>
                    <PrimaryButton class="mt-6 mb-3">
                        {{ props.device ? 'Update' : 'Save' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>