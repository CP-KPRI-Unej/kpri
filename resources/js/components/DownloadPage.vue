<template>
  <div class="download-page">
    <section class="py-12 bg-gray-100 dark:bg-gray-800">
      <div class="container mx-auto px-4">
        <div class="flex flex-col items-center justify-center mb-10">
          <h1 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-4">
            Download Area
          </h1>
          <p class="text-lg text-gray-600 dark:text-gray-300 text-center max-w-2xl">
            Download berbagai dokumen dan file penting dari KPRI
          </p>
        </div>

        <!-- Search and filter -->
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-6 mb-8">
          <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="relative flex-grow">
              <input
                v-model="searchQuery"
                type="text"
                class="w-full border rounded-lg px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                placeholder="Cari dokumen..."
              />
              <span class="absolute left-3 top-2.5">
                <i class="fas fa-search text-gray-400"></i>
              </span>
            </div>
            <div class="flex-shrink-0">
              <select
                v-model="fileTypeFilter"
                class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
              >
                <option value="">Semua Tipe</option>
                <option value="pdf">PDF</option>
                <option value="doc">Word</option>
                <option value="xls">Excel</option>
                <option value="ppt">PowerPoint</option>
                <option value="zip">Archive</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Loading Skeleton -->
        <div v-if="isLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
          <div v-for="n in 6" :key="n" class="bg-white dark:bg-gray-700 rounded-lg shadow animate-pulse">
            <div class="p-5 border-b border-gray-200 dark:border-gray-600">
              <div class="h-6 bg-gray-200 dark:bg-gray-600 rounded w-3/4 mb-3"></div>
              <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-1/2"></div>
            </div>
            <div class="p-5 flex items-center justify-between">
              <div class="h-10 bg-gray-200 dark:bg-gray-600 rounded w-1/3"></div>
              <div class="h-10 bg-gray-200 dark:bg-gray-600 rounded w-1/4"></div>
            </div>
          </div>
        </div>

        <!-- No downloads found message -->
        <div v-else-if="filteredDownloads.length === 0" class="flex flex-col items-center justify-center bg-white dark:bg-gray-700 rounded-lg shadow p-10">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-1">Tidak ada item download</h3>
          <p class="text-gray-500 dark:text-gray-400">Tidak ada dokumen yang tersedia saat ini.</p>
        </div>

        <!-- Download item grid -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
          <div v-for="item in filteredDownloads" :key="item.id" class="bg-white dark:bg-gray-700 rounded-lg shadow transition duration-300 hover:shadow-lg">
            <div class="p-5 border-b border-gray-200 dark:border-gray-600">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1 line-clamp-2">
                {{ item.name }}
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Diupload: {{ formatDate(item.upload_date) }}
              </p>
            </div>
            <div class="p-5 flex items-center justify-between">
              <div class="flex items-center text-gray-500 dark:text-gray-400">
                <span :class="fileIconClass(item.file_extension)" class="text-xl mr-2"></span>
                <span class="text-sm uppercase">{{ item.file_extension }}</span>
              </div>
              <a
                :href="item.file_url"
                target="_blank"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
              >
                <i class="fas fa-download mr-2"></i>
                Download
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'DownloadPage',
  data() {
    return {
      downloads: [],
      isLoading: true,
      searchQuery: '',
      fileTypeFilter: '',
      error: null
    };
  },
  computed: {
    filteredDownloads() {
      if (!this.downloads) return [];
      
      let filtered = this.downloads;
      
      // Filter by search query
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        filtered = filtered.filter(item => 
          item.name.toLowerCase().includes(query)
        );
      }
      
      // Filter by file type
      if (this.fileTypeFilter) {
        filtered = filtered.filter(item => {
          // Group similar extensions
          if (this.fileTypeFilter === 'doc' && ['doc', 'docx'].includes(item.file_extension)) {
            return true;
          }
          if (this.fileTypeFilter === 'xls' && ['xls', 'xlsx'].includes(item.file_extension)) {
            return true;
          }
          if (this.fileTypeFilter === 'ppt' && ['ppt', 'pptx'].includes(item.file_extension)) {
            return true;
          }
          if (this.fileTypeFilter === 'zip' && ['zip', 'rar', '7z'].includes(item.file_extension)) {
            return true;
          }
          return item.file_extension === this.fileTypeFilter;
        });
      }
      
      return filtered;
    }
  },
  mounted() {
    this.fetchDownloads();
  },
  methods: {
    fetchDownloads() {
      this.isLoading = true;
      
      fetch('/api/downloads')
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          this.downloads = data.data;
          this.isLoading = false;
        })
        .catch(error => {
          console.error('Error fetching downloads:', error);
          this.error = 'Gagal memuat data. Silakan coba lagi nanti.';
          this.isLoading = false;
        });
    },
    formatDate(dateString) {
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return new Date(dateString).toLocaleDateString('id-ID', options);
    },
    fileIconClass(extension) {
      extension = extension.toLowerCase();
      
      // Font Awesome icons for different file types
      const iconMap = {
        pdf: 'fas fa-file-pdf text-red-500',
        doc: 'fas fa-file-word text-blue-600',
        docx: 'fas fa-file-word text-blue-600',
        xls: 'fas fa-file-excel text-green-600',
        xlsx: 'fas fa-file-excel text-green-600',
        ppt: 'fas fa-file-powerpoint text-orange-500',
        pptx: 'fas fa-file-powerpoint text-orange-500',
        zip: 'fas fa-file-archive text-purple-500',
        rar: 'fas fa-file-archive text-purple-500',
        '7z': 'fas fa-file-archive text-purple-500',
        // Default icon
        default: 'fas fa-file text-gray-500'
      };
      
      return iconMap[extension] || iconMap.default;
    }
  }
};
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style> 