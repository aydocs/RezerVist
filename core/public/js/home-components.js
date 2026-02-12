
document.addEventListener('alpine:init', () => {
    // Autocomplete Component
    Alpine.data('autocomplete', () => ({
        searchQuery: '',
        suggestions: [],
        showDropdown: false,

        async fetchSuggestions() {
            if (this.searchQuery.length < 2) {
                this.suggestions = [];
                this.showDropdown = false;
                return;
            }

            try {
                const response = await fetch(`/api/autocomplete?q=${encodeURIComponent(this.searchQuery)}`);
                this.suggestions = await response.json();
                this.showDropdown = this.suggestions.length > 0;
            } catch (error) {
                console.error('Autocomplete error:', error);
            }
        },

        selectItem(item) {
            this.searchQuery = item.name;
            this.showDropdown = false;
        }
    }));

    // DatePicker Component
    Alpine.data('datePicker', () => ({
        showDatepicker: false,
        selectedDate: new Date().toISOString().split('T')[0],
        formattedDate: '',
        month: 0,
        year: 0,
        noOfDays: [],
        blankDays: [],
        days: ['Pt', 'Sa', 'Ça', 'Pe', 'Cu', 'Ct', 'Pz'],
        monthNames: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'],

        init() {
            const today = new Date();
            this.month = today.getMonth();
            this.year = today.getFullYear();
            this.formattedDate = `${today.getDate()} ${this.monthNames[this.month]} ${this.year}`;
            this.getDays();
        },

        prevMonth() {
            if (this.month === 0) {
                this.month = 11;
                this.year--;
            } else {
                this.month--;
            }
            this.getDays();
        },

        nextMonth() {
            if (this.month === 11) {
                this.month = 0;
                this.year++;
            } else {
                this.month++;
            }
            this.getDays();
        },

        isPast(date) {
            const d = new Date(this.year, this.month, date);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            return d < today;
        },

        isSelected(date) {
            const d = new Date(this.year, this.month, date);
            const sel = new Date(this.selectedDate);
            return d.toDateString() === sel.toDateString();
        },

        selectDate(date) {
            let d = new Date(this.year, this.month, date);
            const offset = d.getTimezoneOffset();
            d = new Date(d.getTime() - (offset * 60 * 1000));

            this.selectedDate = d.toISOString().split('T')[0];
            this.formattedDate = `${date} ${this.monthNames[this.month]} ${this.year}`;
            // this.showDatepicker = false; // Optional auto-close
        },

        getDays() {
            let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
            let dayOfWeek = new Date(this.year, this.month).getDay();
            let blankdaysArray = [];
            let adjustedDay = dayOfWeek === 0 ? 6 : dayOfWeek - 1;

            for (var i = 1; i <= adjustedDay; i++) {
                blankdaysArray.push(i);
            }

            let daysArray = [];
            for (var i = 1; i <= daysInMonth; i++) {
                daysArray.push(i);
            }

            this.blankDays = blankdaysArray;
            this.noOfDays = daysArray;
        }
    }));

    // GuestPicker Component
    Alpine.data('guestPicker', () => ({
        showGuestPicker: false,
        adults: 2,
        children: 0,

        get totalGuests() {
            return this.adults + this.children;
        },

        get guestText() {
            let text = `${this.adults} Yetişkin`;
            if (this.children > 0) {
                text += `, ${this.children} Çocuk`;
            }
            return text;
        }
    }));
});
