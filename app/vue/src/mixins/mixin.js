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
        }
    }
};
