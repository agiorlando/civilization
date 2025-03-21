import { mount, flushPromises } from '@vue/test-utils';
import Leaders from '@/pages/Leaders.vue';
import axios from 'axios';
import MockAdapter from 'axios-mock-adapter';

describe('Leaders.vue', () => {
  let mock;

  beforeEach(() => {
    mock = new MockAdapter(axios);

    // Mock GET /api/civilizations used by Leaders.vue to fetch available civilizations.
    const defaultCivilizations = [
      { id: 1, name: 'Rome', icon: 'rome.png', leader: { id: 1, name: 'Caesar', civilization_id: 1 } },
      { id: 2, name: 'Egypt', icon: 'egypt.png', leader: null },
    ];
    mock.onGet('/api/civilizations').reply(200, defaultCivilizations);
  });

  afterEach(() => {
    mock.restore();
  });

  it('fetches and displays leaders', async () => {
    const leadersData = [
      {
        id: 1,
        name: 'Caesar',
        civilization_id: 1,
        icon: 'caesar.png',
        subtitle: 'Roman Dictator',
        lifespan: '100-44 BC',
        civilization: { id: 1, name: 'Rome', icon: 'rome.png' },
        historical_info: [],
      },
      {
        id: 2,
        name: 'Cleopatra',
        civilization_id: 2,
        icon: 'cleopatra.png',
        subtitle: 'Queen of Egypt',
        lifespan: '69-30 BC',
        civilization: { id: 2, name: 'Egypt', icon: 'egypt.png' },
        historical_info: [],
      },
    ];
    mock.onGet('/api/leaders').reply(200, leadersData);

    const wrapper = mount(Leaders);
    await flushPromises();

    expect(wrapper.text()).toContain('Caesar');
    expect(wrapper.text()).toContain('Cleopatra');
  });

  it('filters leaders based on search query', async () => {
    const leadersData = [
      {
        id: 1,
        name: 'Caesar',
        civilization_id: 1,
        icon: 'caesar.png',
        subtitle: 'Roman Dictator',
        lifespan: '100-44 BC',
        civilization: { id: 1, name: 'Rome', icon: 'rome.png' },
        historical_info: [],
      },
      {
        id: 2,
        name: 'Cleopatra',
        civilization_id: 2,
        icon: 'cleopatra.png',
        subtitle: 'Queen of Egypt',
        lifespan: '69-30 BC',
        civilization: { id: 2, name: 'Egypt', icon: 'egypt.png' },
        historical_info: [],
      },
    ];
    mock.onGet('/api/leaders').reply(200, leadersData);

    const wrapper = mount(Leaders);
    await flushPromises();

    // Set search query to filter out "Caesar".
    await wrapper.setData({ searchQuery: 'cleopatra' });
    expect(wrapper.text()).toContain('Cleopatra');
    expect(wrapper.text()).not.toContain('Caesar');
  });

  it('opens modal to view leader details when a leader name is clicked', async () => {
    const leaderDetail = {
      id: 1,
      name: 'Caesar',
      civilization_id: 1,
      icon: 'caesar.png',
      subtitle: 'Roman Dictator',
      lifespan: '100-44 BC',
      historical_info: [
        { heading: 'History', text: 'Some historical text about Caesar.' },
      ],
    };

    // For index endpoint, return leaderDetail in an array.
    mock.onGet('/api/leaders').reply(200, [leaderDetail]);
    // When the component requests a single leader detail.
    mock.onGet('/api/leaders/1').reply(200, leaderDetail);

    const wrapper = mount(Leaders);
    await flushPromises();

    // Simulate clicking on the leader's name.
    const clickable = wrapper.find('span.text-blue-600');
    await clickable.trigger('click');
    await flushPromises();

    expect(wrapper.text()).toContain('Roman Dictator');
    expect(wrapper.text()).toContain('Some historical text about Caesar.');
  });
});
