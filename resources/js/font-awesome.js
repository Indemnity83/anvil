import Vue from 'vue'
import {library} from '@fortawesome/fontawesome-svg-core'
import {faTimesCircle} from "@fortawesome/free-regular-svg-icons";
import {faSyncAlt} from "@fortawesome/free-solid-svg-icons";
import {faGithub} from "@fortawesome/free-brands-svg-icons";
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'

library.add(faTimesCircle)
library.add(faGithub)
library.add(faSyncAlt)

Vue.component('font-awesome-icon', FontAwesomeIcon)
