export default [
    /* Deprecated on Tainacan 0.17.2, due to the introduction of support: fontSize */
    {
        attributes: {
            content: {
                type: 'array',
                source: 'children',
                selector: 'div'
            },
            terms: {
                type: Array,
                default: []
            },
            isModalOpen: {
                type: Boolean,
                default: false
            },
            selectedTerms: {
                type: Array,
                default: []
            },
            itemsRequestSource: {
                type: String,
                default: undefined
            },
            maxTermsNumber: {
                type: Number,
                value: undefined
            },
            maxTermsPerScreen: {
                type: Number,
                value: 6
            },
            isLoading: {
                type: Boolean,
                value: false
            },
            isLoadingTerm: {
                type: Boolean,
                value: false
            },
            arrowsPosition: {
                type: String,
                value: 'search'
            },
            largeArrows: {
                type: Boolean,
                value: false
            },
            autoPlay: {
                type: Boolean,
                value: false
            },
            autoPlaySpeed: {
                type: Number,
                value: 3
            },
            loopSlides: {
                type: Boolean,
                value: false
            },
            hideName: {
                type: Boolean,
                value: true
            },
            showTermThumbnail: {
                type: Boolean,
                value: false
            },
            term: {
                type: Object,
                value: undefined
            },
            blockId: {
                type: String,
                default: undefined
            },
            termBackgroundColor: {
                type: String,
                default: "#454647"
            },
            termTextColor: {
                type: String,
                default: "#ffffff"
            },
            taxonomyId: {
                type: String,
                default: undefined
            }
        },
        supports: {
            align: ['full', 'wide'],
            html: false,
            multiple: true
        },
        save({ attributes, className }){
            const {
                content, 
                blockId,
                selectedTerms,
                arrowsPosition,
                largeArrows,
                maxTermsPerScreen,
                maxTermsNumber,
                autoPlay,
                autoPlaySpeed,
                loopSlides,
                hideName,
                showTermThumbnail,
                taxonomyId
            } = attributes;
            return <div 
                        className={ className }
                        selected-terms={ JSON.stringify(selectedTerms.map((term) => { return term.id; })) }
                        arrows-position={ arrowsPosition }
                        auto-play={ '' + autoPlay }
                        auto-play-speed={ autoPlaySpeed }
                        loop-slides={ '' + loopSlides }
                        hide-name={ '' + hideName }
                        large-arrows={ '' + largeArrows }
                        max-terms-number={ maxTermsNumber }
                        max-terms-per-screen={ maxTermsPerScreen }
                        taxonomy-id={ taxonomyId }
                        tainacan-api-root={ tainacan_blocks.root }
                        tainacan-base-url={ tainacan_blocks.base_url }
                        show-term-thumbnail={ '' + showTermThumbnail }
                        id={ 'wp-block-tainacan-carousel-terms-list_' + blockId }>
                            { content }
                    </div>
        }
    },
    {
        attributes: {
            content: {
                type: 'array',
                source: 'children',
                selector: 'div'
            },
            terms: {
                type: Array,
                default: []
            },
            isModalOpen: {
                type: Boolean,
                default: false
            },
            selectedTerms: {
                type: Array,
                default: []
            },
            itemsRequestSource: {
                type: String,
                default: undefined
            },
            maxTermsNumber: {
                type: Number,
                value: undefined
            },
            isLoading: {
                type: Boolean,
                value: false
            },
            isLoadingTerm: {
                type: Boolean,
                value: false
            },
            arrowsPosition: {
                type: String,
                value: 'search'
            },
            autoPlay: {
                type: Boolean,
                value: false
            },
            autoPlaySpeed: {
                type: Number,
                value: 3
            },
            loopSlides: {
                type: Boolean,
                value: false
            },
            hideName: {
                type: Boolean,
                value: true
            },
            showTermThumbnail: {
                type: Boolean,
                value: false
            },
            term: {
                type: Object,
                value: undefined
            },
            blockId: {
                type: String,
                default: undefined
            },
            termBackgroundColor: {
                type: String,
                default: "#454647"
            },
            termTextColor: {
                type: String,
                default: "#ffffff"
            },
            taxonomyId: {
                type: String,
                default: undefined
            }
        },
        save({ attributes, className }){
            const {
                content, 
                blockId,
                selectedTerms,
                arrowsPosition,
                maxTermsNumber,
                autoPlay,
                autoPlaySpeed,
                loopSlides,
                hideName,
                showTermThumbnail,
                taxonomyId
            } = attributes;
            return <div 
                        className={ className }
                        selected-terms={ JSON.stringify(selectedTerms.map((term) => { return term.id; })) }
                        arrows-position={ arrowsPosition }
                        auto-play={ '' + autoPlay }
                        auto-play-speed={ autoPlaySpeed }
                        loop-slides={ '' + loopSlides }
                        hide-name={ '' + hideName }
                        max-terms-number={ maxTermsNumber }
                        taxonomy-id={ taxonomyId }
                        tainacan-api-root={ tainacan_blocks.root }
                        tainacan-base-url={ tainacan_blocks.base_url }
                        show-term-thumbnail={ '' + showTermThumbnail }
                        id={ 'wp-block-tainacan-carousel-terms-list_' + blockId }>
                            { content }
                    </div>
        },
    },
    {
        save({ attributes, className }){
            const {
                content, 
                blockId,
                selectedTerms,
                arrowsPosition,
                maxTermsNumber,
                autoPlay,
                autoPlaySpeed,
                loopSlides,
                hideName,
                showTermThumbnail,
                taxonomyId
            } = attributes;
            return <div 
                        className={ className }
                        selected-terms={ JSON.stringify(selectedTerms) }
                        arrows-position={ arrowsPosition }
                        auto-play={ '' + autoPlay }
                        auto-play-speed={ autoPlaySpeed }
                        loop-slides={ '' + loopSlides }
                        hide-name={ '' + hideName }
                        max-terms-number={ maxTermsNumber }
                        taxonomy-id={ taxonomyId }
                        tainacan-api-root={ tainacan_blocks.root }
                        tainacan-base-url={ tainacan_blocks.base_url }
                        show-term-thumbnail={ '' + showTermThumbnail }
                        id={ 'wp-block-tainacan-carousel-terms-list_' + blockId }>
                            { content }
                    </div>
        }
    }
]