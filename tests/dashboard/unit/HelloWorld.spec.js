import { shallowMount, createLocalVue } from '@vue/test-utils'
import HelloWorld from '@/components/HelloWorld.vue'
import VueRouter from 'vue-router'
import {
    Helpers,
    Button,
} from 'muse-ui';

const localVue = createLocalVue();

localVue.use(Helpers);
localVue.use(Button);
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
