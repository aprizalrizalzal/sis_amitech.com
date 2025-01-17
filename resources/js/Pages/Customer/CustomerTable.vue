<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import CustomerForm from '@/Pages/Customer/CustomerForm.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ButtonImage from '@/Components/ButtonImage.vue';
import BackIcon from '@/Components/Icon/BackIcon.vue';
import NextIcon from '@/Components/Icon/NextIcon.vue';

const props = defineProps({
    customers: {
        type: Array,
        required: true
    }
});

const showingModalCustomerUpdate = ref(false);
const selectedCustomer = ref(null);

const showModalCustomerUpdate = (customer) => {
    selectedCustomer.value = customer;
    showingModalCustomerUpdate.value = true;
};

const confirmingCustomerDeletion = ref(false);
const form = useForm({
    id: null,
});

const confirmCustomerDeletion = (customerId) => {
    confirmingCustomerDeletion.value = true;
    form.id = customerId;
};

const deleteCustomer = () => {
    form.delete(route('destroy.customer'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: (errors) => {
            if (errors) {
                closeModal();
            } else {
                const errorMessages = Object.values(errors).flat();
                alert(`${errorMessages}`);
            }
        }
    });
};

const showingModalUpdateSuccessfully = ref(false);

const showModalUpdateSuccessfully = () => {
    showingModalCustomerUpdate.value = false;
    showingModalUpdateSuccessfully.value = true;
};

const closeModalUpdateSuccessfully = () => {
    showingModalUpdateSuccessfully.value = false;
};

const closeModal = () => {
    showingModalCustomerUpdate.value = false;
    confirmingCustomerDeletion.value = false;
};

const currentPage = ref(1);
const itemsPerPage = 15;

const paginatedCustomers = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return props.customers.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(props.customers.length / itemsPerPage);
});

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

const previousPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};
</script>

<template>
    <div class=" overflow-x-auto">
        <table class="min-w-full bg-white border-collapse">
            <thead>
                <tr>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 truncate">No</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 text-left truncate">Name</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 text-left truncate">Phone</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 text-left truncate">Address</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300" colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(customer, index) in paginatedCustomers" :key="customer.id" class="hover:bg-green-100">
                    <td class="py-2 px-4 border-b border-green-300 text-center">{{ (currentPage - 1) * itemsPerPage +
                        index + 1 }}</td>
                    <td class="py-2 px-4 border-b border-green-300 truncate max-w-xs">
                        {{ customer.user.name }}</td>
                    <td class="py-2 px-4 border-b border-green-300 truncate max-w-xs">
                        {{ customer.phone }}</td>
                    <td class="py-2 px-4 border-b border-green-300 truncate max-w-xs">
                        {{ customer.address }}</td>
                    <td class="py-2 px-4 border-b border-green-300 text-center">
                        <SecondaryButton @click="showModalCustomerUpdate(customer)">Update</SecondaryButton>
                    </td>
                    <td class="py-2 px-4 border-b border-green-300 text-center">
                        <DangerButton @click="confirmCustomerDeletion(customer.id)">Delete</DangerButton>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex justify-center gap-4 items-center p-6">
        <ButtonImage class="py-2" @click="previousPage" :disabled="currentPage === 1">
            <BackIcon />
        </ButtonImage>
        <span>{{ currentPage }} / {{ totalPages }}</span>
        <ButtonImage class="py-2" @click="nextPage" :disabled="currentPage === totalPages">
            <NextIcon />
        </ButtonImage>
    </div>

    <Modal :show="showingModalCustomerUpdate">
        <div class="m-6">
            <div class="flex justify-between items-center ps-6 ms-6 text-green-900">
                <span class="font-bold text-center w-full">Update Customer</span>
                <DangerButton @click="closeModal">X</DangerButton>
            </div>
            <hr class="mt-4 mb-2">
            <CustomerForm :customer="selectedCustomer" @updateCustomer="showModalUpdateSuccessfully" />
        </div>
    </Modal>

    <Modal maxWidth="xl" :show="showingModalUpdateSuccessfully">
        <div class="m-6">
            <div class="flex justify-between items-center ps-6 ms-6 text-green-900">
                <span class="font-bold text-center w-full">Update Customer</span>
                <DangerButton @click="closeModalUpdateSuccessfully">X</DangerButton>
            </div>
            <hr class="mt-4 mb-2">
            <p class="my-4 text-sm text-green-700">
                Customer Update Successful!
            </p>
            <div class="mt-2 flex">
                <PrimaryButton @click="closeModalUpdateSuccessfully">Ok</PrimaryButton>
            </div>
        </div>
    </Modal>

    <Modal :show="confirmingCustomerDeletion">
        <div class="p-6">
            <h2 class="text-lg font-medium text-green-900">
                Are you sure you want to delete your customer?
            </h2>

            <p class="mt-1 text-sm text-green-700">
                Once your customer is deleted, all of its resources and data will be permanently deleted.
            </p>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                <DangerButton class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    @click="deleteCustomer">
                    Delete customer
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>

<style scoped>
/* Custom scrollbar style for overflow-x-auto */
.overflow-x-auto::-webkit-scrollbar {
    display: none;
}

.overflow-x-auto {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
