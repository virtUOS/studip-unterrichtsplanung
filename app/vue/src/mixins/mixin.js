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
        },
        getStructureName(element) {
            let plan = this.$store.state.plan;
            let template = plan.attributes.templates_id;

            return this.getStructureNameByTemplate(element, template);
        },
        getStructureNameByTemplate(element, template) {
            if (template == 1) {
                return element.attributes.name;
            }

            if (template == 2) { // Mathematik
                if(element.attributes.id == 7) {
                    return 'Zusammensetzung der Klasse';
                } else {
                    return element.attributes.name;
                }
            }

            if (template == 3) { // Sport
                switch(element.attributes.id) {
                    case '6': 
                        return 'Medien, Geräte und Materialien';
                        break;
                    case '7': 
                        return 'Zusammensetzung der Klasse';
                        break;
                    case '13':
                        return 'Vorwissen und Vorerfahrungen';
                        break;
                    case '17':
                        return 'Körperliche Konstitution';
                        break;
                    default:
                        return element.attributes.name;
                }
            }

            if (template == 4) { // Geographie
                return element.attributes.name;
            }
        }
    }
};
