<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import FooterForm from '@/Pages/Setting/Footer/FooterForm.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import ButtonImage from '@/Components/ButtonImage.vue';
import EditIcon from '@/Components/Icon/EditIcon.vue';
import BackIcon from '@/Components/Icon/BackIcon.vue';
import NextIcon from '@/Components/Icon/NextIcon.vue';

const props = defineProps({
    footers: Array,

    typeFooters: Array,
    platformFooters: Array,
});

const showingModalFooterUpdateImage = ref(false);
const showingModalFooterUpdate = ref(false);
const selectedFooterId = ref(null);
const selectedFooter = ref(null);

const confirmingFooterDeletion = ref(false);
const form = useForm({
    id: null,
});

const showModalFooterUpdateImage = (footerId) => {
    selectedFooterId.value = footerId;
    showingModalFooterUpdateImage.value = true;
};

const showModalFooterUpdate = (footer) => {
    selectedFooter.value = footer;
    showingModalFooterUpdate.value = true;
};

const confirmFooterDeletion = (footerId) => {
    confirmingFooterDeletion.value = true;
    form.id = footerId;
};

const deleteFooter = () => {
    form.delete(route('destroy.footer', { id: form.id }), {
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

const showingModalUpdateImageSuccessfully = ref(false);

const showModalUpdateImageSuccessfully = () => {
    showingModalFooterUpdateImage.value = false;
    showingModalUpdateImageSuccessfully.value = true;
};

const closeModalUpdateImageSuccessfully = () => {
    showingModalUpdateImageSuccessfully.value = false;
};

const showingModalUpdateSuccessfully = ref(false);

const showModalUpdateSuccessfully = () => {
    showingModalFooterUpdate.value = false;
    showingModalUpdateSuccessfully.value = true;
};

const closeModalUpdateSuccessfully = () => {
    showingModalUpdateSuccessfully.value = false;
};

const closeModal = () => {
    confirmingFooterDeletion.value = false;
    showingModalFooterUpdate.value = false;
    showingModalFooterUpdateImage.value = false;
};

const currentPage = ref(1);
const itemsPerPage = 5;

const paginatedFooters = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return props.footers.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(props.footers.length / itemsPerPage);
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
        <table class="min-w-full bg-white border-collapse divide-y divide-gray-300">
            <thead>
                <tr>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 truncate">No</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 truncate">Image</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 text-left truncate">Type</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 text-left truncate">Platform</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 text-left truncate">URL</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 text-left truncate">Username</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 text-left truncate">Value</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300" colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(footer, index) in paginatedFooters" :key="footer.id" class="hover:bg-green-100">
                    <td class="py-2 px-4 border-b border-green-300 text-center">
                        {{ (currentPage - 1) * itemsPerPage +
                            index + 1 }}</td>
                    <td class="py-2 px-4 border-b border-green-300 truncate">
                        <div class="flex justify-center items-center m-2">
                            <img :src="`${footer.image_path}`" :alt="footer.type_footer.type"
                                class="h-full object-cover rounded me-2" style="max-width: 40px;" />
                            <ButtonImage @click="showModalFooterUpdateImage(footer.id)">
                                <EditIcon />
                            </ButtonImage>
                        </div>
                    </td>
                    <td class="py-2 px-4 border-b border-green-300 truncate max-w-xs">
                        {{ footer.type_footer.type }}</td>
                    <td class="py-2 px-4 border-b border-green-300 truncate max-w-xs">
                        {{ footer.platform_footer.platform }}
                    </td>
                    <td class="py-2 px-4 border-b border-green-300 truncate max-w-xs">
                        {{ footer.url }}</td>
                    <td class="py-2 px-4 border-b border-green-300 truncate max-w-xs">
                        {{ footer.username }}</td>
                    <td class="py-2 px-4 border-b border-green-300 truncate max-w-xs">
                        {{ footer.value }}
                    </td>
                    <td class="py-2 px-4 border-b border-green-300 text-center">
                        <SecondaryButton @click="showModalFooterUpdate(footer)">Update
                        </SecondaryButton>
                    </td>
                    <td class="py-2 px-4 border-b border-green-300 text-center">
                        <DangerButton @click="confirmFooterDeletion(footer.id)">Delete</DangerButton>
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

    <Modal :show="showingModalFooterUpdateImage">
        <div class="m-6">
            <div class="flex justify-between items-center ps-6 ms-6 text-green-900">
                <span class="font-bold text-center w-full">Update Image Footer</span>
                <DangerButton @click="closeModal">X</DangerButton>
            </div>
            <hr class="mt-4 mb-2">
            <FooterForm :footerId="selectedFooterId" @updateImageFooter="showModalUpdateImageSuccessfully" />
        </div>
    </Modal>

    <Modal maxWidth="xl" :show="showingModalUpdateImageSuccessfully">
        <div class="m-6">
            <div class="flex justify-between items-center ps-6 ms-6 text-green-900">
                <span class="font-bold text-center w-full">Update Image Footer</span>
                <DangerButton @click="closeModalUpdateImageSuccessfully">X</DangerButton>
            </div>
            <hr class="mt-4 mb-2">
            <p class="my-4 text-sm text-green-700">
                Footer Update Image Successful!
            </p>
            <div class="mt-2 flex">
                <PrimaryButton @click="closeModalUpdateImageSuccessfully">Ok</PrimaryButton>
            </div>
        </div>
    </Modal>

    <Modal :show="showingModalFooterUpdate">
        <div class="m-6">
            <div class="flex justify-between items-center ps-6 ms-6 text-green-900">
                <span class="font-bold text-center w-full">Update Footer</span>
                <DangerButton @click="closeModal">X</DangerButton>
            </div>
            <hr class="mt-4 mb-2">
            <FooterForm :footer="selectedFooter" :typeFooters="typeFooters" :platformFooters="platformFooters"
                @updateFooter="showModalUpdateSuccessfully" />
        </div>
    </Modal>

    <Modal maxWidth="xl" :show="showingModalUpdateSuccessfully">
        <div class="m-6">
            <div class="flex justify-between items-center ps-6 ms-6 text-green-900">
                <span class="font-bold text-center w-full">Update Footer</span>
                <DangerButton @click="closeModalUpdateSuccessfully">X</DangerButton>
            </div>
            <hr class="mt-4 mb-2">
            <p class="my-4 text-sm text-green-700">
                Footer Update Successful!
            </p>
            <div class="mt-2 flex">
                <PrimaryButton @click="closeModalUpdateSuccessfully">Ok</PrimaryButton>
            </div>
        </div>
    </Modal>

    <Modal :show="confirmingFooterDeletion">
        <div class="p-6">
            <h2 class="text-lg font-medium text-green-900">
                Are you sure you want to delete this footer?
            </h2>

            <p class="mt-1 text-sm text-green-700">
                Once your footer is deleted, all of its resources and data will be permanently deleted.
            </p>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                <DangerButton class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    @click="deleteFooter">
                    Delete
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