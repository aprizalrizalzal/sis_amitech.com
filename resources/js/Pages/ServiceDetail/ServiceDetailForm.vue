<script setup>
import { ref, watch } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import DropdownSelect from '@/Components/DropdownSelect.vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const { auth } = usePage().props;
const userId = ref(auth.user.id);

const form = useForm({
    user_id: userId.value,
    service_id: '',
    status_service_id: '',
    repair_description: '',
    cost: '',
    notes: '',
});

const props = defineProps({
    users: Array,
    services: Array,

    statusServices: Array,

    printServiceDetail: String,

    serviceDetail: Object,

    user: Object,
    service: Object,
});

const serviceName = ref('');
const serviceEmail = ref('');
const servicePhone = ref('');
const serviceStatus = ref('');

if (props.serviceDetail) {
    form.user_id = props.serviceDetail.user_id;
    form.service_id = props.serviceDetail.service_id;
    form.repair_description = props.serviceDetail.repair_description;
    form.cost = props.serviceDetail.cost;
    form.notes = props.serviceDetail.notes;
    const selectedService = props.services.find(service => service.id === form.service_id);
    if (selectedService) {
        serviceName.value = selectedService.customer.user.name;
        serviceEmail.value = selectedService.customer.user.email;
        servicePhone.value = selectedService.customer.phone;
        serviceStatus.value = selectedService.status_service.status;
        form.status_service_id = selectedService.status_service.id;
    }
}

watch(() => form.service_id, (newServiceId) => {
    const selectedService = props.services.find(service => service.id === newServiceId);
    if (selectedService) {
        serviceName.value = selectedService.customer.user.name;
        serviceEmail.value = selectedService.customer.user.email;
        servicePhone.value = selectedService.customer.phone;
        serviceStatus.value = selectedService.status_service.status;
        form.status_service_id = selectedService.status_service.id;
    }
});

const submitForm = () => {
    if (!props.serviceDetail) {
        form.post(route('store.service.detail'), {
            preserveScroll: true,
            onSuccess: (page) => {
                form.reset('cost', 'notes', 'repair_description');
                emit('addServiceDetail');
                const printServiceDetail = page.props.printServiceDetail;
                if (printServiceDetail) {
                    window.open(printServiceDetail, '_blank');
                };
            },
            onError: (errors) => {
                if (errors.user_id || errors.service_id || errors.status_service_id || errors.repair_description || errors.cost || errors.notes) {
                    alert('addition failed!');
                } else {
                    const errorMessages = Object.values(errors).flat();
                    alert(`${errorMessages}`);
                }
            }
        });
    } else {
        const serviceDetailId = props.serviceDetail.id
        form.put(route('update.service.detail', { id: serviceDetailId }), {
            preserveScroll: true,
            onSuccess: () => {
                form.data(),
                    emit('updateServiceDetail');
            },
            onError: (errors) => {
                if (errors.user_id || errors.service_id || errors.status_service_id || errors.repair_description || errors.cost || errors.notes) {
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
        'addServiceDetail',
        'updateServiceDetail'
    ]
);
</script>

<template>
    <div class="relative flex w-full flex-1 items-stretch">
        <div class="w-full">
            <form @submit.prevent="submitForm" class="mt-3 space-y-3">
                <div v-if="!props.user" class="hidden">
                    <InputLabel for="user_id" value="Technician" />
                    <TextInput id="user_id" type="text" class="mt-1 block w-full" v-model="form.user_id"
                        placeholder="Email Technician" required autofocus />
                    <InputError class="mt-2" :message="form.errors.user_id" />
                </div>
                <div>
                    <DropdownSelect id="service_id" label="Service Code" optionProperty="service_code"
                        valueProperty="id" :options="services" v-model="form.service_id"
                        :placeholder='props.service ? props.service.service_code : "Service Code"' class="w-full" />
                    <InputError class="mt-2" :message="form.errors.service_id" />
                </div>
                <div v-if="form.service_id">
                    <InputLabel for="name" value="Customer Name" />
                    <TextInput id="name" type="text" class="mt-1 block w-full" :placeholder="serviceName" disabled />
                </div>
                <div v-if="form.service_id">
                    <InputLabel for="email" value="Email" />
                    <TextInput id="email" type="text" class="mt-1 block w-full" :placeholder="serviceEmail" disabled />
                </div>
                <div v-if="form.service_id">
                    <InputLabel for="phone" value="Phone" />
                    <TextInput id="phone" type="text" class="mt-1 block w-full" :placeholder="servicePhone" disabled />
                </div>
                <div v-if="form.service_id">
                    <DropdownSelect id="status_service_id" label="Status" optionProperty="status" valueProperty="id"
                        :options="statusServices" v-model="form.status_service_id"
                        :placeholder='form.service_id ? serviceStatus : "Select Status"' class="mt-1 block w-full" />
                    <InputError class="mt-2" :message="form.errors.status_service_id" />
                </div>
                <hr v-if="form.service_id">
                <div>
                    <InputLabel for="repair_description" value="Repair Description" />
                    <TextInput id="repair_description" type="text" class="mt-1 block w-full"
                        v-model="form.repair_description" placeholder="Repair Description" required />
                    <InputError class="mt-2" :message="form.errors.repair_description" />
                </div>
                <div>
                    <InputLabel for="cost" value="Cost" />
                    <TextInput id="cost" type="text" class="mt-1 block w-full" v-model="form.cost" placeholder="Cost"
                        required />
                    <InputError class="mt-2" :message="form.errors.cost" />
                </div>
                <div>
                    <InputLabel for="notes" value="Notes" />
                    <textarea id="notes" type="text"
                        class="mt-1 block w-full border-green-700 focus:border-green-700 focus:ring-green-700 rounded shadow"
                        v-model="form.notes" placeholder="Notes" required />
                    <InputError class="mt-2" :message="form.errors.notes" />
                </div>
                <div>
                    <PrimaryButton class="mt-6 mb-3">
                        {{ props.user ? 'Update' : 'Save' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
