export default {
    methods: {
        getPlanTemplateName(templates_id) {
            let fullText = '';
            switch (templates_id) {
                case '1':
                    fullText = 'Allgemeindidaktisch';
                    break;
                case '2':
                    fullText = 'Mathematikdidaktisch';
                    break;
                case '3':
                    fullText = 'Sportdidaktisch';
                    break;
                case '4':
                    fullText = 'Geographiedidaktisch';
                    break;
            }
            return fullText;
        },
        getFromatedDate(date) {
            const newDate = new Date(date);
            let day = newDate.getDate();
            let month = newDate.getMonth() + 1;
            if (day < 10) {day = '0' + day;}
            if (month < 10) {month = '0' + month;}

            return day + '.' + month + '.' + newDate.getFullYear();
        }
    }
};
