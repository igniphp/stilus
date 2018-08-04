import { shallowMount, createLocalVue } from '@vue/test-utils'
import HelloWorld from '@/components/HelloWorld.vue'
import VueRouter from 'vue-router'
import 'muse-ui/lib/styles/base.less';
import 'muse-ui/lib/styles/theme.less';
import {
    Grid,
    Helpers,
    Button,
    Select
} from 'muse-ui';

const localVue = createLocalVue();
localVue.use(Button);
localVue.use(Select);
localVue.use(Grid);
localVue.use(Helpers);
localVue.use(VueRouter);


const router = new VueRouter();

describe('HelloWorld.vue', () => {
  it('renders props.msg when passed', () => {
    const msg = 'Go to main';
    const wrapper = shallowMount(HelloWorld, {
        localVue,
        router,
        propsData: { msg }
    });
    expect(wrapper.text()).toMatch(msg);
  })
});
