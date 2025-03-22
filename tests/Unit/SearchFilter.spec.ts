import { mount } from '@vue/test-utils';
import SearchFilter from '@/components/SearchFilter.vue';

describe('SearchFilter.vue', () => {
  it('renders an input with the correct placeholder', () => {
    const wrapper = mount(SearchFilter, {
      props: { modelValue: '', placeholder: 'Search here' }
    });
    const input = wrapper.find('input');
    expect(input.attributes('placeholder')).toBe('Search here');
  });

  it('emits update:modelValue when typing', async () => {
    const wrapper = mount(SearchFilter, { props: { modelValue: '' } });
    const input = wrapper.find('input');
    await input.setValue('test');
    expect(wrapper.emitted('update:modelValue')).toBeTruthy();
    expect(wrapper.emitted('update:modelValue')?.[0]).toEqual(['test']);
  });
});
