export default {
    methods: {
        getPlanTemplateName(templates_id) {
            let fullText = '';
            switch (templates_id) {
                case '1':
                    fullText = 'Bildungswissenschaftlich';
                    break;
                case '2':
                    fullText = 'Fachdidaktik Mathematik';
                    break;
                case '3':
                    fullText = 'Fachdidaktik Sport';
                    break;
                case '4':
                    fullText = 'Fachdidaktik Geologie';
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
