import { mount, flushPromises } from '@vue/test-utils';
import Civilizations from '@/pages/Civilizations.vue';
import axios from 'axios';
import MockAdapter from 'axios-mock-adapter';

describe('Civilizations.vue', () => {
  let mock;
  beforeEach(() => {
    mock = new MockAdapter(axios);
  });
  afterEach(() => {
    mock.restore();
  });

  it('fetches and displays civilizations', async () => {
    const data = [
      { id: 1, name: 'Rome', icon: 'rome.png', leader: { id: 10, name: 'Caesar' }, historical_info: [] },
      { id: 2, name: 'Egypt', icon: 'egypt.png', leader: null, historical_info: [] },
    ];
    mock.onGet('/api/civilizations').reply(200, data);
    const wrapper = mount(Civilizations);
    await flushPromises();
    expect(wrapper.text()).toContain('Rome');
    expect(wrapper.text()).toContain('Egypt');
  });

  it('filters civilizations based on search query', async () => {
    const data = [
      { id: 1, name: 'Rome', icon: 'rome.png', leader: { id: 10, name: 'Caesar' }, historical_info: [] },
      { id: 2, name: 'Egypt', icon: 'egypt.png', leader: null, historical_info: [] },
    ];
    mock.onGet('/api/civilizations').reply(200, data);
    const wrapper = mount(Civilizations);
    await flushPromises();
    await wrapper.setData({ searchQuery: 'egypt' });
    expect(wrapper.text()).toContain('Egypt');
    expect(wrapper.text()).not.toContain('Rome');
  });
});
