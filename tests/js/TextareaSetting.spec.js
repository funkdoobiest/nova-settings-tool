import { shallowMount } from '@vue/test-utils'
import TextareaSetting from '@/TextareaSetting'

describe('Textarea setting', () => {

    it('sets `id` to the setting key', () => {
        const wrapper = shallowMount(TextareaSetting, {
            propsData: {
                setting: {
                    key: 'text_setting',
                },
            },
        })

        expect(wrapper.find('.form-input').attributes('id'))
            .toBe('text_setting')
    })

    it('displays the passed setting value', () => {
        const wrapper = shallowMount(TextareaSetting, {
            propsData: {
                setting: {
                    key: 'text_setting',
                    value: 'Some text',
                },
            },
        })

        expect(wrapper.find('.form-input').element.value)
            .toBe('Some text')
    })

    it('emits an updated setting object on input', () => {
        const wrapper = shallowMount(TextareaSetting, {
            propsData: {
                setting: {
                    label: 'Text Setting',
                    key: 'text_setting',
                },
            },
        })

        wrapper.find('.form-input').setValue('New value')
        wrapper.find('.form-input').trigger('input')

        expect(wrapper.emitted().update[0])
            .toEqual([
                {
                    key: 'text_setting',
                    value: 'New value'
                }
            ])
    })

})
