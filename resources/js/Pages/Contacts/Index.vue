<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-50 py-8">
      <!-- Flash Messages -->
      <TransitionGroup 
        name="fade" 
        tag="div" 
        class="fixed top-4 right-4 z-50 space-y-4"
      >
        <!-- Success Message -->
        <div
          v-if="showSuccessMessage && $page.props.flash?.success"
          :key="'success'"
          class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-lg flex items-center transform transition-all duration-500 hover:scale-105"
          role="alert"
        >
          <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-green-100 text-green-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-green-800">{{ $page.props.flash.success }}</p>
          </div>
        </div>

        <!-- Error Message -->
        <div
          v-if="showErrorMessage && $page.props.flash?.error"
          :key="'error'"
          class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-lg flex items-center transform transition-all duration-500 hover:scale-105"
          role="alert"
        >
          <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-red-100 text-red-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-red-800">{{ $page.props.flash.error }}</p>
          </div>
        </div>
      </TransitionGroup>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
          <h2 class="text-3xl font-bold text-gray-900">Contacts</h2>
          <button
            @click="openContactModal()"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition-all duration-200 hover:scale-105"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Contact
          </button>
        </div>

        <!-- Contact List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="contact in contacts.data" :key="contact.id" class="hover:bg-gray-50 transition-colors duration-200">
                  <td class="px-6 py-4">
                    <div class="flex items-center">
                      <div class="h-10 w-10 flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                          <span class="text-indigo-700 font-medium text-sm">{{ contact.name.charAt(0).toUpperCase() }}</span>
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ contact.name }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="text-sm text-gray-900">{{ contact.email }}</div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="text-sm text-gray-900">{{ formatPhone(contact.phone) }}</div>
                  </td>
                  <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                    <button
                      @click="openContactModal(contact)"
                      class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200"
                    >
                      <span class="sr-only">Edit</span>
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                    </button>
                    <button
                      @click="deleteContact(contact)"
                      class="text-red-600 hover:text-red-900 transition-colors duration-200"
                    >
                      <span class="sr-only">Delete</span>
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                    </button>
                  </td>
                </tr>
                <tr v-if="contacts.data.length === 0">
                  <td colspan="4" class="px-6 py-10 text-center">
                    <div class="text-gray-500">
                      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                      </svg>
                      <p class="mt-2 text-sm font-medium">No contacts found</p>
                      <p class="mt-1 text-sm text-gray-500">Get started by creating a new contact.</p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6" v-if="contacts.data.length > 0">
          <Pagination :links="contacts.links" />
        </div>

        <!-- Contact Modal -->
        <Modal v-if="showingContactModal" @close="closeContactModal">
          <div class="p-6">
            <div class="mb-5">
              <h3 class="text-lg font-medium text-gray-900">
                {{ contactBeingUpdated ? 'Edit Contact' : 'Add New Contact' }}
              </h3>
              <p class="mt-1 text-sm text-gray-600">
                {{ contactBeingUpdated ? 'Update the contact information below.' : 'Fill in the information below to create a new contact.' }}
              </p>
            </div>
            
            <form @submit.prevent="submitForm" class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input
                  type="text"
                  v-model="form.name"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors duration-200"
                  required
                  minlength="3"
                />
                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input
                  type="email"
                  v-model="form.email"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors duration-200"
                  required
                />
                <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input
                  type="text"
                  v-model="form.phone"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors duration-200"
                  required
                  placeholder="(99) 99999-9999"
                  @input="formatPhoneInput"
                />
                <p v-if="form.errors.phone" class="mt-1 text-sm text-red-600">{{ form.errors.phone }}</p>
              </div>

              <div class="flex justify-end space-x-3">
                <button
                  type="button"
                  @click="closeContactModal"
                  class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                  :disabled="form.processing"
                >
                  <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                  </svg>
                  {{ contactBeingUpdated ? 'Update Contact' : 'Create Contact' }}
                </button>
              </div>
            </form>
          </div>
        </Modal>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useForm, usePage, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Modal from '@/Components/Modal.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  contacts: {
    type: Object,
    required: true,
    default: () => ({
      data: [],
      links: [],
    }),
  },
})

const page = usePage()
const showingContactModal = ref(false)
const contactBeingUpdated = ref(null)
const showSuccessMessage = ref(false)
const showErrorMessage = ref(false)

const form = useForm({
  name: '',
  email: '',
  phone: '',
})

const formatPhone = (phone) => {
  if (!phone) return ''
  const cleaned = phone.replace(/\D/g, '')
  const match = cleaned.match(/^(\d{2})(\d{4,5})(\d{4})$/)
  if (match) {
    return `(${match[1]}) ${match[2]}-${match[3]}`
  }
  return phone
}

const formatPhoneInput = (event) => {
  let value = event.target.value.replace(/\D/g, '')
  if (value.length > 11) value = value.substring(0, 11)
  
  if (value.length >= 2) {
    value = `(${value.substring(0, 2)}) ${value.substring(2)}`
  }
  if (value.length >= 10) {
    const pos = value.length >= 11 ? 10 : 9
    value = value.substring(0, pos) + '-' + value.substring(pos)
  }
  form.phone = value
}

const openContactModal = (contact = null) => {
  if (contact) {
    contactBeingUpdated.value = contact
    form.name = contact.name
    form.email = contact.email
    form.phone = formatPhone(contact.phone)
  } else {
    contactBeingUpdated.value = null
    form.reset()
  }
  showingContactModal.value = true
}

const closeContactModal = () => {
  showingContactModal.value = false
  form.reset()
  form.clearErrors()
  contactBeingUpdated.value = null
}

const submitForm = () => {
  if (contactBeingUpdated.value) {
    form.put(`/contacts/${contactBeingUpdated.value.id}`, {
      onSuccess: () => closeContactModal(),
      preserveScroll: true,
    })
  } else {
    form.post('/contacts', {
      onSuccess: () => {
        closeContactModal();
        window.location.href = route('contacts.index');
      },
      onError: (errors) => {
        console.error('Erros no envio do formulário:', errors);
      },
      preserveScroll: true,
    })
  }
}

const deleteContact = (contact) => {
  if (confirm('Are you sure you want to delete this contact?')) {
    form.delete(`/contacts/${contact.id}`, {
      preserveScroll: true,
      preserveState: true,
      onSuccess: () => {
        props.contacts.data = props.contacts.data.filter(c => c.id !== contact.id);
      },
    });
  }
}

// Watch for changes in flash messages
watch(() => page.props.flash?.success, (newValue) => {
  if (newValue) {
    showSuccessMessage.value = true
    setTimeout(() => {
      showSuccessMessage.value = false
    }, 3000)
  }
})

watch(() => page.props.flash?.error, (newValue) => {
  if (newValue) {
    showErrorMessage.value = true
    setTimeout(() => {
      showErrorMessage.value = false
    }, 3000)
  }
})
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateX(30px);
}

/* Adicione estas classes para melhorar as transições */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}

.hover\:scale-105:hover {
  transform: scale(1.05);
}

/* Animação de loading */
@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style> 