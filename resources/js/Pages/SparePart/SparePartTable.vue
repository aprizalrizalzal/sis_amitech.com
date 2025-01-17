<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import SparePartForm from './SparePartForm.vue';
import ImageSparePartForm from './Image/ImageSparePartForm.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ButtonImage from '@/Components/ButtonImage.vue';
import EraserIcon from '@/Components/Icon/EraserIcon.vue';
import EditIcon from '@/Components/Icon/EditIcon.vue';
import SparePartDetail from './SparePartDetail.vue';
import ImagesIcon from '@/Components/Icon/ImagesIcon.vue';
import BackIcon from '@/Components/Icon/BackIcon.vue';
import NextIcon from '@/Components/Icon/NextIcon.vue';


const props = defineProps({
    spareParts: Array,
    categorySpareParts: Array,
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(value);
};

const showingModalSparePartUpdateImage = ref(false);
const showingModalSparePartAddImages = ref(false);
const showingModalSparePartUpdate = ref(false);
const showingModalSparePartDetail = ref(false);
const selectedSparePartId = ref(null);
const selectedSparePart = ref(null);

const showModalSparePartUpdateImage = (sparePartId) => {
    selectedSparePartId.value = sparePartId;
    showingModalSparePartUpdateImage.value = true;
};

const showModalSparePartAddImages = (sparePart) => {
    selectedSparePart.value = sparePart;
    showingModalSparePartAddImages.value = true;
};

const showModalSparePartUpdate = (sparePart) => {
    selectedSparePart.value = sparePart;
    showingModalSparePartUpdate.value = true;
};

const showModalSparePartDetail = (sparePart) => {
    selectedSparePart.value = sparePart;
    showingModalSparePartDetail.value = true;
};

const confirmingSparePartDeletion = ref(false);

const form = useForm({
    id: null,
});

const confirmSparePartDeletion = (sparePartId) => {
    confirmingSparePartDeletion.value = true;
    form.id = sparePartId;
};

const deleteSparePart = () => {
    form.delete(route('destroy.spare.part'), {
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

const deleteImageSparePart = () => {
    form.delete(route('destroy.image.spare.part.image'), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
        },
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
    showingModalSparePartUpdateImage.value = false;
    showingModalUpdateImageSuccessfully.value = true;
};

const closeModalUpdateImageSuccessfully = () => {
    showingModalUpdateImageSuccessfully.value = false;
};

const showingModalUpdateSuccessfully = ref(false);

const showModalUpdateSuccessfully = () => {
    showingModalSparePartUpdate.value = false;
    showingModalUpdateSuccessfully.value = true;
};

const closeModalUpdateSuccessfully = () => {
    showingModalUpdateSuccessfully.value = false;
};

const showingModalAddImageSuccessfully = ref(false);

const showModalAddImageSuccessfully = () => {
    showingModalSparePartAddImages.value = false;
    showingModalAddImageSuccessfully.value = true;
};

const closeModalAddImageSuccessfully = () => {
    showingModalAddImageSuccessfully.value = false;
};

const confirmingImageSparePartDeletion = ref(false);

const confirmImageSparePartDeletion = (imageSparePartId) => {
    confirmingImageSparePartDeletion.value = true;
    form.id = imageSparePartId;
};

const closeModal = () => {
    showingModalSparePartUpdateImage.value = false;
    showingModalSparePartAddImages.value = false;
    showingModalSparePartUpdate.value = false;
    showingModalSparePartDetail.value = false;
    confirmingSparePartDeletion.value = false;
    confirmingImageSparePartDeletion.value = false;
};

const currentPage = ref(1);
const itemsPerPage = 15;

const paginatedSpareParts = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return props.spareParts.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(props.spareParts.length / itemsPerPage);
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
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border-collapse">
            <thead>
                <tr>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 truncate">No</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 truncate">Cover</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 truncate">Image</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 text-left truncate">Name</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 text-left truncate">Category</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 truncate">Pieces</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 text-left truncate">Price</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300 text-left truncate">Description</th>
                    <th class="py-4 px-4 border-b border-green-300 bg-green-300" colspan="3">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(sparePart, index) in paginatedSpareParts" :key="sparePart.id" class="hover:bg-green-100">
                    <td class="py-2 px-4 border-b border-green-300 text-center">
                        {{ (currentPage - 1) * itemsPerPage +
                            index + 1 }}</td>
                    <td class="py-2 px-4 border-b border-green-300 truncate">
                        <div class="flex justify-center items-center m-2">
                            <img :src="`${sparePart.image_path}`" :alt="sparePart.name"
                                class="h-16 w-16 object-cover rounded me-2" style="max-width: 128px;" />
                            <ButtonImage @click="showModalSparePartUpdateImage(sparePart.id)">
                                <EditIcon />
                            </ButtonImage>
                        </div>
                    </td>
                    <td class="py-2 px-4 border-b border-green-300 truncate">
                        <div class="flex items-center">
                            <div v-for="(imageSparePart) in sparePart.image_spare_parts" :key="imageSparePart.id"
                                class="relative me-2">
                                <img :src="`${imageSparePart.image_path}`" :alt="sparePart.name"
                                    class="h-16 w-16 object-cover rounded " style="max-width: 128px;" />
                                <ButtonImage @click="confirmImageSparePartDeletion(imageSparePart.id)"
                                    class="absolute top-0.5 right-0.5 inline-flex items-center p-0.5 bg-white border border-red-700 rounded font-semibold text-xs text-red-900 tracking-widest shadow hover:bg-red-50 focus:outline-none focus:ring-1 focus:ring-red-900 disabled:opacity-25 transition ease-in-out duration-150">
                                    <EraserIcon />
                                </ButtonImage>
                            </div>
                            <ButtonImage @click="showModalSparePartAddImages(sparePart)">
                                <ImagesIcon />
                            </ButtonImage>
                        </div>
                    </td>
                    <td class="py-2 px-4 border-b border-green-300 truncate max-w-xs">
                        {{ sparePart.name }}
                    </td>
                    <td class="py-2 px-4 border-b border-green-300 truncate max-w-xs">
                        {{ sparePart.category_spare_part.name }}
                    </td>
                    <td class="py-2 px-4 border-b border-green-300 text-center truncate max-w-xs">
                        {{ sparePart.pieces }}
                    </td>
                    <td class="py-2 px-4 border-b border-green-300 truncate">
                        {{ formatCurrency(sparePart.price) }}
                    </td>
                    <td class="py-2 px-4 border-b border-green-300 truncate max-w-xs">
                        {{ sparePart.description }}
                    </td>
                    <td class="py-2 px-4 border-b border-green-300 text-center">
                        <SecondaryButton @click="showModalSparePartUpdate(sparePart)">Update
                        </SecondaryButton>
                    </td>
                    <td class="py-2 px-4 border-b border-green-300 text-center">
                        <PrimaryButton @click="showModalSparePartDetail(sparePart)">Detail
                        </PrimaryButton>
                    </td>
                    <td class="py-2 px-4 border-b border-green-300 text-center">
                        <DangerButton @click="confirmSparePartDeletion(sparePart.id)">Delete</DangerButton>
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

    <Modal :show="showingModalSparePartUpdateImage">
        <div class="m-6">
            <div class="flex justify-between items-center ps-6 ms-6 text-green-900">
                <span class="font-bold text-center w-full">Update Cover Spare Part</span>
                <DangerButton @click="closeModal">X</DangerButton>
            </div>
            <hr class="mt-4 mb-2">
            <SparePartForm :sparePartId="selectedSparePartId"
                @updateImageSparePart="showModalUpdateImageSuccessfully" />
        </div>
    </Modal>

    <Modal maxWidth="xl" :show="showingModalUpdateImageSuccessfully">
        <div class="m-6">
            <div class="flex justify-between items-center ps-6 ms-6 text-green-900">
                <span class="font-bold text-center w-full">Update Cover Spare Part</span>
                <DangerButton @click="closeModalUpdateImageSuccessfully">X</DangerButton>
            </div>
            <hr class="mt-4 mb-2">
            <p class="my-4 text-sm text-green-700">
                Cover Spare Parts Update Successful!
            </p>
            <div class="mt-2 flex">
                <PrimaryButton @click="closeModalUpdateImageSuccessfully">Ok</PrimaryButton>
            </div>
        </div>
    </Modal>

    <Modal :show="showingModalSparePartUpdate">
        <div class="m-6">
            <div class="flex justify-between items-center ps-6 ms-6 text-green-900">
                <span class="font-bold text-center w-full">Update Spare Part</span>
                <DangerButton @click="closeModal">X</DangerButton>
            </div>
            <hr class="mt-4 mb-2">
            <SparePartForm :sparePart="selectedSparePart" :categorySpareParts="categorySpareParts"
                @updateSparePart="showModalUpdateSuccessfully" />
        </div>
    </Modal>

    <Modal maxWidth="xl" :show="showingModalUpdateSuccessfully">
        <div class="m-6">
            <div class="flex justify-between items-center ps-6 ms-6 text-green-900">
                <span class="font-bold text-center w-full">Update Spare Part</span>
                <DangerButton @click="closeModalUpdateSuccessfully">X</DangerButton>
            </div>
            <hr class="mt-4 mb-2">
            <p class="my-4 text-sm text-green-700">
                Spare Parts Update Successful!
            </p>
            <div class="mt-2 flex">
                <PrimaryButton @click="closeModalUpdateSuccessfully">Ok</PrimaryButton>
            </div>
        </div>
    </Modal>

    <Modal :show="showingModalSparePartAddImages">
        <div class="m-6">
            <div class="flex justify-between items-center ps-6 ms-6 text-green-900">
                <span class="font-bold text-center w-full">Add Image Spare Part</span>
                <DangerButton @click="closeModal">X</DangerButton>
            </div>
            <hr class="mt-4 mb-2">
            <ImageSparePartForm :sparePart="selectedSparePart" @addSparePartImages="showModalAddImageSuccessfully" />
        </div>
    </Modal>

    <Modal maxWidth="xl" :show="showingModalAddImageSuccessfully">
        <div class="m-6">
            <div class="flex justify-between items-center ps-6 ms-6 text-green-900">
                <span class="font-bold text-center w-full">Add Image Spare Part</span>
                <DangerButton @click="closeModalAddImageSuccessfully">X</DangerButton>
            </div>
            <hr class="mt-4 mb-2">
            <p class="my-4 text-sm text-green-700">
                Adding Image Spare Parts Successfully!
            </p>
            <div class="mt-2 flex">
                <PrimaryButton @click="closeModalAddImageSuccessfully">Ok</PrimaryButton>
            </div>
        </div>
    </Modal>

    <Modal maxWidth="7xl" :show="showingModalSparePartDetail">
        <div class="m-6">
            <div class="flex justify-between items-center ps-6 ms-6 text-green-900">
                <span class="font-bold text-center w-full">Detail Spare Part</span>
                <DangerButton @click="closeModal">X</DangerButton>
            </div>
            <hr class="mt-4 mb-2">
            <SparePartDetail :sparePart="selectedSparePart" />
        </div>
    </Modal>

    <Modal :show="confirmingSparePartDeletion">
        <div class="p-6">
            <h2 class="text-lg font-medium text-green-900">
                Are you sure you want to delete your Spare Part?
            </h2>
            <p class="mt-1 text-sm text-green-700">
                Once your Spare Part is deleted, all of its resources and data will be permanently deleted.
            </p>
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                <DangerButton class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    @click="deleteSparePart">
                    Delete
                </DangerButton>
            </div>
        </div>
    </Modal>

    <Modal :show="confirmingImageSparePartDeletion">
        <div class="p-6">
            <h2 class="text-lg font-medium text-green-900">
                Are you sure you want to delete your Image Spare Part?
            </h2>
            <p class="mt-1 text-sm text-green-700">
                Once your Image Spare Part is deleted, all of its resources and data will be permanently deleted.
            </p>
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                <DangerButton class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    @click="deleteImageSparePart">
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
