<?php
/**
 * Copyright (c) 2013, Praxigento
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the
 * following conditions are met:
 *  - Redistributions of source code must retain the above copyright notice, this list of conditions and the following
 *      disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the
 *      following disclaimer in the documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
/**
 * User: Alex Gusev <flancer64@gmail.com>
 */

namespace dBEAR\Schema\Comparator;


use dBEAR\Schema\Domain\Entity;

class CompareEntities
{
    /**
     * Return 'true' in case of two entities are enough equal (except notes, etc.).
     * @param $entity1
     * @param $entity2
     * @return bool
     */
    public static function equalsEnough($entity1, $entity2)
    {
        $result = false;
        if (($entity1 instanceof Entity) && ($entity2 instanceof Entity)) {
            if ($entity1->getAlias() == $entity2->getAlias()) {
                $attrs1 = $entity1->getAttributes();
                $attrs2 = $entity2->getAttributes();
                if (count($attrs1) == count($attrs2)) {
                    $result = true;
                    foreach ($entity1->getAttributes() as $alias => $one) {
                        if (!CompareAttributes::equalsEnough($one, $entity2->getAttribute($alias))) {
                            $result = false;
                            break;
                        }
                    }
                }
            }
        }
        return $result;
    }
}